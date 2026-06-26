<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Console;

use Illuminate\Support\Str;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

use Simtabi\Laranail\Licence\Verifier\Presets\Generators\GeneratedPackage;
use Simtabi\Laranail\Licence\Verifier\Presets\Generators\PresetPackageGenerator;
use Simtabi\Laranail\Licence\Verifier\Presets\Presets\PresetDefinition;
use Simtabi\Laranail\Licence\Verifier\Presets\Presets\PresetRegistry;
use Simtabi\Laranail\Licence\Verifier\Presets\Themes\Theme;
use Simtabi\Laranail\Licence\Verifier\Presets\Validation\NamespaceValidator;
use Simtabi\Laranail\Licence\Verifier\Presets\Validation\PathValidator;
use Simtabi\Laranail\Package\Tools\Commands\Command;
use Simtabi\Laranail\Package\Tools\Concerns\Package\ManagesComposer;
use Throwable;

/**
 * Generate ONE owned preset package (the user's namespace/path/theme) that
 * extends the installed preset base package, then register it in the app via
 * composer. One preset at a time.
 */
final class InstallPresetCommand extends Command
{
    use ManagesComposer;

    protected $signature = 'laranail::license-verifier-ui.install
        {preset? : Which preset to install (blade|livewire|filament|vue)}
        {--force : Overwrite an existing target directory}
        {--no-symlink : Copy the package instead of symlinking the path repository}
        {--no-install : Generate files only; do not run composer}';

    protected $description = 'Generate and install one license-verifier UI preset package';

    public function handle(PresetRegistry $registry, PresetPackageGenerator $generator): int
    {
        if ($registry->all() === []) {
            $this->components->warn(
                'No preset packages are installed. Require one first, e.g. '
                .'`composer require laranail/license-verifier-ui-blade`.'
            );

            return self::FAILURE;
        }

        $key = $this->resolvePreset($registry);
        if ($key === null) {
            return self::FAILURE;
        }

        $def = $registry->get($key);

        $theme = $def->supportsTheme(Theme::FILAMENT) && count($def->supportedThemes) === 1
            ? Theme::FILAMENT
            : select(
                label: 'Which UI theme?',
                options: collect($def->supportedThemes)->mapWithKeys(fn (string $t): array => [$t => Theme::label($t)])->all(),
                default: $def->defaultTheme,
            );

        $composerName = text(
            label: 'Composer package name (vendor/package)',
            default: $this->defaultComposerName($key),
            required: true,
            validate: static fn (string $v): ?string => str_contains(trim($v, '/'), '/') ? null : 'Use vendor/package format.',
        );
        [$vendor, $package] = explode('/', $composerName, 2);

        $namespace = $this->resolveNamespace($vendor, $package, $key);

        $pathValidator = new PathValidator(base_path());
        $path = text(
            label: 'Where to create the package (relative to your app root)',
            placeholder: 'packages/licensing/'.$key,
            required: true,
            validate: fn (string $value): ?string => $pathValidator->validate($value, (bool) $this->option('force')),
            hint: 'You must provide a path; it has no default.',
        );

        $register = $this->option('no-install')
            ? 'generate'
            : select(
                label: 'How should it be wired in?',
                options: ['register' => 'Register via composer now', 'generate' => 'Generate files only'],
                default: 'register',
            );

        $pkg = new GeneratedPackage(
            presetKey: $key,
            theme: $theme,
            namespace: $namespace,
            path: $path,
            vendor: $vendor,
            package: $package,
            basePackage: $def->composerRequire,
            frameworkRequire: $def->frameworkRequire,
        );

        try {
            $written = $generator->generate($pkg, $def, base_path(), (bool) $this->option('force'));
        } catch (Throwable $e) {
            $this->components->error($e->getMessage());

            return self::FAILURE;
        }

        $this->components->info(sprintf('Generated %d files into %s', count($written), $path));

        if ($register === 'register') {
            if (! $this->registerWithComposer($pkg)) {
                return self::FAILURE;
            }
        } else {
            $this->printManualComposer($pkg);
        }

        $this->printPostNotes($key, $namespace);

        return self::SUCCESS;
    }

    private function resolvePreset(PresetRegistry $registry): ?string
    {
        $arg = $this->argument('preset');

        if (is_string($arg) && $arg !== '') {
            if ($registry->has($arg)) {
                return $arg;
            }
            $this->components->warn(
                "The \"{$arg}\" preset package is not installed. Require it first: "
                ."`composer require laranail/license-verifier-ui-{$arg}`."
            );

            return null;
        }

        return select(
            label: 'Which preset do you want to install?',
            options: collect($registry->all())->map(fn (PresetDefinition $def): string => $def->label)->all(),
        );
    }

    private function defaultComposerName(string $key): string
    {
        $vendor = config('license-verifier-ui.composer_vendor');

        if (! is_string($vendor) || trim($vendor) === '') {
            $vendor = 'app';
            $root = base_path('composer.json');
            if (is_file($root)) {
                $name = json_decode((string) file_get_contents($root), true)['name'] ?? null;
                if (is_string($name) && str_contains($name, '/')) {
                    $vendor = explode('/', $name, 2)[0];
                }
            }
        }

        return $vendor.'/license-verifier-'.$key;
    }

    /**
     * Resolve the generated package namespace: offer a value derived from the
     * vendor/package name, or let the user enter one explicitly. Both paths are
     * validated; the explicit prompt is the fallback when the derived value is
     * not a valid PSR-4 namespace.
     */
    private function resolveNamespace(string $vendor, string $package, string $key): string
    {
        $validator = new NamespaceValidator;
        $derived = $this->deriveNamespace($vendor, $package);
        $derivable = $validator->isValid($derived);

        $choice = select(
            label: 'How should the PHP namespace be set?',
            options: [
                'derive' => $derivable
                    ? "Derive from vendor/package ({$derived})"
                    : 'Derive from vendor/package (unavailable — name is not a valid namespace)',
                'explicit' => 'Enter a namespace explicitly',
            ],
            default: $derivable ? 'derive' : 'explicit',
        );

        if ($choice === 'derive' && $derivable) {
            return $derived;
        }

        return text(
            label: 'PHP namespace for the generated package',
            default: $derivable ? $derived : 'App\\Licensing\\'.Str::studly($key),
            required: true,
            validate: fn (string $value): ?string => $validator->validate($value),
            hint: 'Your own namespace; the generated classes live here and extend the base package.',
        );
    }

    /** Derive a StudlyCase PSR-4 namespace from the composer vendor/package. */
    private function deriveNamespace(string $vendor, string $package): string
    {
        return Str::studly($vendor).'\\'.Str::studly($package);
    }

    private function registerWithComposer(GeneratedPackage $pkg): bool
    {
        if (! $this->isComposerAvailable()) {
            $this->components->warn('Composer was not found on PATH. Generated files only.');
            $this->printManualComposer($pkg);

            return true;
        }

        $this->components->info('Registering the package via composer…');
        $this->withComposerErrors();

        if (! $this->addComposerRepository($pkg->vendor, $pkg->package, $pkg->path, ! $this->option('no-symlink'))) {
            $this->components->error('Failed to add the path repository.');

            return false;
        }

        if (! $this->composerRequire($pkg->vendor, $pkg->package, '@dev')) {
            $this->removeComposerRepository($pkg->vendor, $pkg->package);
            $this->components->error('composer require failed; rolled back the repository.');
            $this->printManualComposer($pkg);

            return false;
        }

        $this->composerDumpAutoload();
        $this->components->info('Registered '.$pkg->composerName().' and dumped the autoloader.');

        return true;
    }

    private function printManualComposer(GeneratedPackage $pkg): void
    {
        $repo = json_encode(['type' => 'path', 'url' => $pkg->path, 'options' => ['symlink' => true]]);
        $this->components->bulletList([
            "composer config repositories.{$pkg->vendorKebab()}/{$pkg->packageKebab()} '{$repo}'",
            'composer require '.$pkg->composerName().':@dev',
        ]);
    }

    private function printPostNotes(string $key, string $namespace): void
    {
        if ($key === 'filament') {
            $this->components->info("Add to your PanelProvider: \$panel->plugin(\\{$namespace}\\LicenseVerifierPlugin::make());");
        }

        if ($key === 'vue') {
            $this->components->info('Import the SFC into your Vite entry and rebuild assets (see the generated README).');
        }
    }
}

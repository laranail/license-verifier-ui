<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Console;

use Illuminate\Filesystem\Filesystem;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;

use Simtabi\Laranail\Package\Tools\Commands\Command;
use Simtabi\Laranail\Package\Tools\Concerns\Package\ManagesComposer;

/**
 * Remove a generated preset package: composer-remove it and drop its path
 * repository, then optionally delete its directory (kept by default).
 */
final class UninstallPresetCommand extends Command
{
    use ManagesComposer;

    protected $signature = 'laranail::license-verifier-ui.uninstall
        {--delete-files : Also delete the generated package directory}';

    protected $description = 'Uninstall a generated license-verifier UI preset package';

    public function handle(Filesystem $files): int
    {
        $installed = $this->installedGeneratedPackages();

        if ($installed === []) {
            $this->components->info('No generated license-verifier UI preset packages are installed.');

            return self::SUCCESS;
        }

        $name = select(
            label: 'Which generated preset package do you want to uninstall?',
            options: collect($installed)->keys()->mapWithKeys(fn (string $n): array => [$n => $n])->all(),
        );

        if (! confirm("Uninstall {$name}?", default: true)) {
            return self::SUCCESS;
        }

        [$vendor, $package] = explode('/', (string) $name, 2);

        $this->withComposerErrors();
        if ($this->isComposerAvailable() && ! $this->uninstallLocalPackage($vendor, $package)) {
            $this->components->error('composer remove failed; leaving files in place.');

            return self::FAILURE;
        }

        $url = $installed[$name];
        $delete = (bool) $this->option('delete-files')
            || confirm("Also delete the directory {$url}?", default: false);

        if ($delete && is_string($url) && $files->isDirectory(base_path($url))) {
            $files->deleteDirectory(base_path($url));
            $this->components->info("Deleted {$url}.");
        }

        $this->components->info("Uninstalled {$name}.");

        return self::SUCCESS;
    }

    /**
     * Discover generated packages from the root composer.json path repositories
     * whose package requires a laranail/license-verifier-ui-* base.
     *
     * @return array<string, string> composer name => relative url
     */
    private function installedGeneratedPackages(): array
    {
        $composer = base_path('composer.json');
        if (! is_file($composer)) {
            return [];
        }

        $data = json_decode((string) file_get_contents($composer), true);
        $found = [];

        foreach ((array) ($data['repositories'] ?? []) as $repo) {
            if (($repo['type'] ?? null) !== 'path') {
                continue;
            }
            if (! isset($repo['url'])) {
                continue;
            }
            $pkgJson = base_path($repo['url']).'/composer.json';
            if (! is_file($pkgJson)) {
                continue;
            }

            $pkg = json_decode((string) file_get_contents($pkgJson), true);
            $name = $pkg['name'] ?? null;
            $requires = array_keys((array) ($pkg['require'] ?? []));

            $isPreset = (bool) array_filter(
                $requires,
                static fn (string $r): bool => str_starts_with($r, 'laranail/license-verifier-ui-'),
            );

            if (is_string($name) && $isPreset) {
                $found[$name] = $repo['url'];
            }
        }

        return $found;
    }
}

<?php

declare(strict_types=1);

/**
 * Render every preset × supported theme into ./generated-preview for quality
 * review. Pure file generation (no Laravel runtime needed).
 *
 *   php scripts/generate-preview.php
 */

require __DIR__.'/../vendor/autoload.php';

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Simtabi\Laranail\Licence\Verifier\Presets\Blade\Presets\BladePresetDefinition;
use Simtabi\Laranail\Licence\Verifier\Presets\Filament\Presets\FilamentPresetDefinition;
use Simtabi\Laranail\Licence\Verifier\Presets\Generators\GeneratedPackage;
use Simtabi\Laranail\Licence\Verifier\Presets\Generators\PresetPackageGenerator;
use Simtabi\Laranail\Licence\Verifier\Presets\Generators\StubRenderer;
use Simtabi\Laranail\Licence\Verifier\Presets\Livewire\Presets\LivewirePresetDefinition;
use Simtabi\Laranail\Licence\Verifier\Presets\Vue\Presets\VuePresetDefinition;

$root = dirname(__DIR__);
$out = $root.'/generated-preview';

$files = new Filesystem;
$files->deleteDirectory($out);
$files->ensureDirectoryExists($out);

$generator = new PresetPackageGenerator(new StubRenderer, $files);

$defs = [
    'blade' => BladePresetDefinition::make($root.'/presets/blade/stubs'),
    'livewire' => LivewirePresetDefinition::make($root.'/presets/livewire/stubs'),
    'filament' => FilamentPresetDefinition::make($root.'/presets/filament/stubs'),
    'vue' => VuePresetDefinition::make($root.'/presets/vue/stubs'),
];

$packages = 0;
$total = 0;
$problems = [];

foreach ($defs as $key => $def) {
    foreach ($def->supportedThemes as $theme) {
        $pkg = new GeneratedPackage(
            presetKey: $key,
            theme: $theme,
            namespace: 'App\\Licensing\\'.Str::studly($key),
            path: "{$key}/{$theme}",
            vendor: 'acme',
            package: "license-verifier-{$key}-{$theme}",
            basePackage: $def->composerRequire,
            frameworkRequire: $def->frameworkRequire,
        );

        $written = $generator->generate($pkg, $def, $out, force: true);
        $packages++;
        $total += count($written);

        // Quality gates: no leftover tokens; PHP parses.
        foreach ($written as $file) {
            if (preg_match('/\$[A-Z][A-Z0-9_]*\$/', (string) $files->get($file))) {
                $problems[] = "leftover token: {$file}";
            }
            if (str_ends_with($file, '.php')) {
                $lint = shell_exec('php -l '.escapeshellarg($file).' 2>&1');
                if (! str_contains((string) $lint, 'No syntax errors')) {
                    $problems[] = "php syntax: {$file}";
                }
            }
            if (in_array(basename($file), ['composer.json', 'package.json'], true) && json_decode((string) $files->get($file)) === null) {
                $problems[] = "invalid JSON: {$file}";
            }
        }

        printf("  %-22s → %2d files\n", "{$key}/{$theme}", count($written));
    }
}

echo "\n{$packages} packages, {$total} files in generated-preview/\n";

if ($problems !== []) {
    echo "\nPROBLEMS:\n - ".implode("\n - ", $problems)."\n";
    exit(1);
}

echo "All preview packages: no leftover tokens, valid PHP, valid composer.json.\n";

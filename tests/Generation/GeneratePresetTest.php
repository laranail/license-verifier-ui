<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Simtabi\Laranail\Licence\Verifier\Presets\Generators\GeneratedPackage;
use Simtabi\Laranail\Licence\Verifier\Presets\Generators\PresetPackageGenerator;
use Simtabi\Laranail\Licence\Verifier\Presets\Presets\PresetRegistry;

it('registers the blade preset definition', function (): void {
    expect(app(PresetRegistry::class)->has('blade'))->toBeTrue();
});

it('generates a blade tailwind package with all tokens resolved', function (): void {
    $def = app(PresetRegistry::class)->get('blade');
    $generator = app(PresetPackageGenerator::class);

    $tmp = sys_get_temp_dir().'/lvui-'.uniqid();
    mkdir($tmp, 0777, true);

    $pkg = new GeneratedPackage(
        presetKey: 'blade',
        theme: 'tailwind',
        namespace: 'App\\Licensing\\Blade',
        path: 'packages/licensing/blade',
        vendor: 'acme',
        package: 'license-verifier-blade',
        basePackage: 'laranail/license-verifier-ui-blade',
    );

    $written = $generator->generate($pkg, $def, $tmp, force: true);
    $root = $tmp.'/packages/licensing/blade';

    // Expected files exist.
    foreach ([
        'composer.json',
        '.gitignore',
        'README.md',
        'src/Providers/BladePresetServiceProvider.php',
        'src/Http/Controllers/LicenseController.php',
        'routes/web.php',
        'config/license-verifier-blade.php',
        'resources/views/unlicensed.blade.php',
        'resources/views/license-form.blade.php',
        'resources/js/license-verifier.js',
    ] as $rel) {
        expect(is_file($root.'/'.$rel))->toBeTrue("missing {$rel}");
    }

    // composer.json is valid and correctly wired.
    $composer = json_decode((string) file_get_contents($root.'/composer.json'), true);
    expect($composer)->not->toBeNull()
        ->and($composer['name'])->toBe('acme/license-verifier-blade')
        ->and($composer['autoload']['psr-4'])->toHaveKey('App\\Licensing\\Blade\\')
        ->and($composer['require'])->toHaveKey('laranail/license-verifier-ui-blade')
        ->and($composer['extra']['laravel']['providers'][0])->toBe('App\\Licensing\\Blade\\Providers\\BladePresetServiceProvider');

    // Generated provider lives under Providers/, extends the base, bakes in the literals.
    expect(file_get_contents($root.'/src/Providers/BladePresetServiceProvider.php'))
        ->toContain('namespace App\\Licensing\\Blade\\Providers;')
        ->toContain('extends BaseBladePresetServiceProvider')
        ->toContain("return 'license-verifier-blade';");

    // The view cross-include was retargeted to the view namespace.
    expect(file_get_contents($root.'/resources/views/unlicensed.blade.php'))
        ->toContain("@include('license-verifier-blade::license-form')")
        ->toContain('vendor/license-verifier-blade/license-verifier.js');

    // No leftover tokens, and generated PHP is syntactically valid.
    foreach ($written as $file) {
        expect(preg_match('/\$[A-Z][A-Z0-9_]*\$/', (string) file_get_contents($file)))->toBe(0, "leftover token in {$file}");
    }
    expect(shell_exec('php -l '.escapeshellarg($root.'/src/Http/Controllers/LicenseController.php')))
        ->toContain('No syntax errors');
});

it('generates every supported blade theme cleanly', function (string $theme): void {
    $def = app(PresetRegistry::class)->get('blade');
    $generator = app(PresetPackageGenerator::class);
    $tmp = sys_get_temp_dir().'/lvui-'.uniqid();
    mkdir($tmp, 0777, true);

    $pkg = new GeneratedPackage(
        presetKey: 'blade', theme: $theme, namespace: 'App\\Licensing\\Blade',
        path: 'pkg', vendor: 'acme', package: 'license-verifier-blade',
        basePackage: 'laranail/license-verifier-ui-blade',
    );

    $written = $generator->generate($pkg, $def, $tmp, force: true);
    $root = $tmp.'/pkg';

    expect(is_file($root.'/resources/views/unlicensed.blade.php'))->toBeTrue()
        ->and(is_file($root.'/resources/views/license-form.blade.php'))->toBeTrue()
        ->and(is_file($root.'/resources/js/license-verifier.js'))->toBeTrue();

    foreach ($written as $file) {
        expect(preg_match('/\$[A-Z][A-Z0-9_]*\$/', (string) file_get_contents($file)))->toBe(0, "leftover token in {$file}");
    }
})->with(['tailwind', 'bootstrap', 'alpine', 'unstyled', 'custom']);

it('registers all four preset definitions', function (): void {
    expect(app(PresetRegistry::class)->keys())->toContain('blade', 'livewire', 'filament', 'vue');
});

it('generates every installed preset × theme with valid composer + no leftover tokens', function (): void {
    $registry = app(PresetRegistry::class);
    $generator = app(PresetPackageGenerator::class);

    foreach ($registry->all() as $def) {
        foreach ($def->supportedThemes as $theme) {
            $tmp = sys_get_temp_dir().'/lvui-'.uniqid();
            mkdir($tmp, 0777, true);

            $pkg = new GeneratedPackage(
                presetKey: $def->key,
                theme: $theme,
                namespace: 'App\\Licensing\\'.Str::studly($def->key),
                path: 'pkg',
                vendor: 'acme',
                package: 'license-verifier-'.$def->key,
                basePackage: $def->composerRequire,
                frameworkRequire: $def->frameworkRequire,
            );

            $written = $generator->generate($pkg, $def, $tmp, force: true);
            $root = $tmp.'/pkg';
            $label = "{$def->key}/{$theme}";

            // Valid composer.json wired to the right base + framework.
            $composer = json_decode((string) file_get_contents($root.'/composer.json'), true);
            expect($composer)->not->toBeNull("{$label} composer.json invalid")
                ->and(array_keys($composer['require']))->toContain($def->composerRequire);

            if ($def->frameworkRequire !== null) {
                [$fw] = explode(':', (string) $def->frameworkRequire, 2);
                expect(array_keys($composer['require']))->toContain($fw);
            }

            // A generated package.json (Vue) must also be valid JSON.
            if (is_file($root.'/package.json')) {
                expect(json_decode((string) file_get_contents($root.'/package.json'), true))
                    ->not->toBeNull("{$label} package.json invalid");
            }

            // No leftover tokens anywhere.
            foreach ($written as $file) {
                expect(preg_match('/\$[A-Z][A-Z0-9_]*\$/', (string) file_get_contents($file)))
                    ->toBe(0, "leftover token in {$label}: {$file}");
            }

            // The generated provider is syntactically valid PHP.
            expect(shell_exec('php -l '.escapeshellarg($root.'/src/Providers/'.Str::studly($def->key).'PresetServiceProvider.php')))
                ->toContain('No syntax errors');

            // The provider is wired under the Providers sub-namespace.
            expect($composer['extra']['laravel']['providers'][0])->toContain('\\Providers\\');
        }
    }
});

it('refuses an unsupported theme', function (): void {
    $def = app(PresetRegistry::class)->get('blade');
    $generator = app(PresetPackageGenerator::class);

    $pkg = new GeneratedPackage(
        presetKey: 'blade', theme: 'filament', namespace: 'App\\Licensing\\Blade',
        path: 'x', vendor: 'acme', package: 'p', basePackage: 'laranail/license-verifier-ui-blade',
    );

    expect(fn () => $generator->generate($pkg, $def, sys_get_temp_dir().'/lvui-'.uniqid(), true))
        ->toThrow(RuntimeException::class);
});

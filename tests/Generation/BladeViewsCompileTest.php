<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Simtabi\Laranail\Licence\Verifier\Presets\Generators\GeneratedPackage;
use Simtabi\Laranail\Licence\Verifier\Presets\Generators\PresetPackageGenerator;
use Simtabi\Laranail\Licence\Verifier\Presets\Presets\PresetRegistry;

/**
 * Only the `unlicensed` view (+ its `license-form` include) is exercised through
 * a real route. This compiles EVERY generated Blade view for every theme and
 * lints the produced PHP, so a syntax error or unbalanced directive in any view
 * stub (activation-modal, status-widget, settings-panel, …) fails the build.
 */
it('compiles every Blade view to valid PHP for each theme', function (string $theme): void {
    $def = app(PresetRegistry::class)->get('blade');
    $tmp = sys_get_temp_dir().'/lvui-blade-views-'.$theme.'-'.uniqid();
    mkdir($tmp, 0777, true);

    $pkg = new GeneratedPackage(
        presetKey: 'blade',
        theme: $theme,
        namespace: 'Acme\\BladeViews'.Str::studly($theme),
        path: 'pkg',
        vendor: 'acme',
        package: 'license-verifier-blade-'.$theme,
        basePackage: 'laranail/license-verifier-ui-blade',
    );

    app(PresetPackageGenerator::class)->generate($pkg, $def, $tmp, force: true);

    $views = glob($tmp.'/pkg/resources/views/*.blade.php');
    expect($views)->not->toBeEmpty();

    foreach ($views as $file) {
        $compiledPath = $file.'.compiled.php';
        file_put_contents($compiledPath, Blade::compileString((string) file_get_contents($file)));

        $lint = (string) shell_exec('php -l '.escapeshellarg($compiledPath));
        expect($lint)->toContain('No syntax errors');
    }
})->with(['tailwind', 'bootstrap', 'alpine', 'unstyled', 'custom']);

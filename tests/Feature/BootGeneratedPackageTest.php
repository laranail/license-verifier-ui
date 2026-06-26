<?php

declare(strict_types=1);

use Simtabi\Laranail\Licence\Verifier\Presets\Generators\GeneratedPackage;
use Simtabi\Laranail\Licence\Verifier\Presets\Generators\PresetPackageGenerator;
use Simtabi\Laranail\Licence\Verifier\Presets\Presets\PresetRegistry;

/**
 * End-to-end "ready to use" proof: generate a Blade package, register its
 * provider exactly as package discovery would, and exercise the live routes
 * against the verifier null driver.
 */
it('boots a generated blade package and serves a working license UI', function (): void {
    $def = app(PresetRegistry::class)->get('blade');

    $tmp = sys_get_temp_dir().'/lvui-boot-'.uniqid();
    mkdir($tmp, 0777, true);

    $pkg = new GeneratedPackage(
        presetKey: 'blade',
        theme: 'unstyled',
        namespace: 'Acme\\BootBlade',
        path: 'pkg',
        vendor: 'acme',
        package: 'license-verifier-blade-boot',
        basePackage: 'laranail/license-verifier-ui-blade',
    );

    app(PresetPackageGenerator::class)->generate($pkg, $def, $tmp, force: true);
    $root = $tmp.'/pkg';

    // Autoload the generated classes (package discovery would do this via composer)
    // and register the provider the same way Laravel would.
    require $root.'/src/Http/Controllers/LicenseController.php';
    require $root.'/src/Providers/BladePresetServiceProvider.php';
    $this->app->register('Acme\\BootBlade\\Providers\\BladePresetServiceProvider');

    // The unlicensed page renders the driver-aware form...
    $this->get('license/unlicensed')
        ->assertOk()
        ->assertSee('name="license_key"', false);

    // ...and the JSON endpoints work against the null driver.
    $this->postJson('license/activate', ['license_key' => 'DEV-KEY'])
        ->assertOk()
        ->assertJsonPath('data.valid', true);

    $this->getJson('license/status')->assertOk()->assertJsonPath('valid', true);
});

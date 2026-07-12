<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Livewire\Livewire;
use Simtabi\Laranail\Licence\Verifier\Presets\Generators\GeneratedPackage;
use Simtabi\Laranail\Licence\Verifier\Presets\Generators\PresetPackageGenerator;
use Simtabi\Laranail\Licence\Verifier\Presets\Presets\PresetRegistry;

/**
 * Boot the OTHER generated presets (Blade is covered by BootGeneratedPackageTest)
 * exactly as package discovery would — register the generated provider and
 * exercise its real surface against the verifier null driver.
 */

/** Generate a preset package into a fresh temp dir and return its root path. */
function generatePresetPackage(string $key, string $theme, string $namespace): string
{
    $def = app(PresetRegistry::class)->get($key);
    $tmp = sys_get_temp_dir().'/lvui-boot-'.$key.'-'.uniqid();
    mkdir($tmp, 0777, true);

    $pkg = new GeneratedPackage(
        presetKey: $key,
        theme: $theme,
        namespace: $namespace,
        path: 'pkg',
        vendor: 'acme',
        package: 'license-verifier-'.$key,
        basePackage: $def->composerRequire,
        frameworkRequire: $def->frameworkRequire,
    );

    app(PresetPackageGenerator::class)->generate($pkg, $def, $tmp, force: true);

    return $tmp.'/pkg';
}

it('renders every Blade theme unlicensed page', function (string $theme): void {
    $ns = 'Acme\\BootBladeTheme'.Str::studly($theme);
    $root = generatePresetPackage('blade', $theme, $ns);

    require $root.'/src/Http/Controllers/LicenseController.php';
    require $root.'/src/Providers/BladePresetServiceProvider.php';
    $this->app->register($ns.'\\Providers\\BladePresetServiceProvider');

    $this->get('license/unlicensed')
        ->assertOk()
        ->assertSee('name="license_key"', false);
})->with(['tailwind', 'bootstrap', 'alpine', 'unstyled', 'custom']);

it('surfaces the configured redirect_after_activation on the Blade form', function (): void {
    $ns = 'Acme\\BootBladeRedirect';
    $root = generatePresetPackage('blade', 'tailwind', $ns);

    require $root.'/src/Http/Controllers/LicenseController.php';
    require $root.'/src/Providers/BladePresetServiceProvider.php';
    $this->app->register($ns.'\\Providers\\BladePresetServiceProvider');

    config()->set('license-verifier-blade.redirect_after_activation', '/dashboard');

    $this->get('license/unlicensed')
        ->assertOk()
        ->assertSee('data-lv-redirect="/dashboard"', false);
});

it('boots a generated Vue package and serves the JSON endpoints', function (): void {
    $ns = 'Acme\\BootVue';
    $root = generatePresetPackage('vue', 'unstyled', $ns);

    require $root.'/src/Http/Controllers/LicenseController.php';
    require $root.'/src/Providers/VuePresetServiceProvider.php';
    $this->app->register($ns.'\\Providers\\VuePresetServiceProvider');

    $this->getJson('license/status')->assertOk()->assertJsonPath('valid', true);
    $this->postJson('license/activate', ['license_key' => 'DEV-KEY'])
        ->assertOk()
        ->assertJsonPath('data.valid', true);
});

it('boots a generated Livewire package and the activation form activates', function (string $theme): void {
    $ns = 'Acme\\BootLivewire'.Str::studly($theme);
    $root = generatePresetPackage('livewire', $theme, $ns);

    require $root.'/src/Components/ActivationForm.php';
    require $root.'/src/Components/StatusWidget.php';
    require $root.'/src/Providers/LivewirePresetServiceProvider.php';
    $this->app->register($ns.'\\Providers\\LivewirePresetServiceProvider');

    Livewire::test($ns.'\\Components\\ActivationForm')
        ->assertOk()
        ->set('licenseKey', 'DEV-KEY')
        ->call('activate')
        ->assertSet('activated', true);
})->with(['tailwind', 'bootstrap', 'unstyled', 'custom']);

it('boots a generated Filament package: provider, plugin and classes load', function (): void {
    $ns = 'Acme\\BootFilament';
    $root = generatePresetPackage('filament', 'filament', $ns);

    require $root.'/src/Filament/Pages/LicensePage.php';
    require $root.'/src/Filament/Widgets/LicenseStatusWidget.php';
    require $root.'/src/LicenseVerifierPlugin.php';
    require $root.'/src/Providers/FilamentPresetServiceProvider.php';
    $this->app->register($ns.'\\Providers\\FilamentPresetServiceProvider');

    $pluginClass = $ns.'\\LicenseVerifierPlugin';

    expect($pluginClass::make()->getId())->toBe('license-verifier')
        ->and(class_exists($ns.'\\Filament\\Pages\\LicensePage'))->toBeTrue()
        ->and(class_exists($ns.'\\Filament\\Widgets\\LicenseStatusWidget'))->toBeTrue();
});

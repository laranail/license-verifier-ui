<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Simtabi\Laranail\Licence\Verifier\Presets\Presets\PresetRegistry;
use Simtabi\Laranail\Licence\Verifier\Presets\Generators\GeneratedPackage;
use Simtabi\Laranail\Licence\Verifier\Presets\Generators\PresetPackageGenerator;

/**
 * Route names a generated package registers. A newly generated package uses the
 * vendor-scoped prefix; a package generated before the change carries the old
 * prefix in its own config and views, and must keep working unchanged.
 */

/** Generate a preset package, rewrite its route prefix if asked, and boot it. */
function bootPrefixedPresetPackage(string $key, string $namespace, ?string $rewritePrefixTo = null): string
{
    $def = app(PresetRegistry::class)->get($key);
    $tmp = sys_get_temp_dir() . '/lvui-prefix-' . $key . '-' . uniqid();
    mkdir($tmp, 0777, true);

    $pkg = new GeneratedPackage(
        presetKey: $key,
        theme: 'unstyled',
        namespace: $namespace,
        path: 'pkg',
        vendor: 'acme',
        package: 'license-verifier-' . $key,
        basePackage: $def->composerRequire,
        frameworkRequire: $def->frameworkRequire,
    );

    app(PresetPackageGenerator::class)->generate($pkg, $def, $tmp, force: true);
    $root = $tmp . '/pkg';

    if ($rewritePrefixTo !== null) {
        // Reproduce a package generated before the prefix changed: the old prefix
        // is baked into its config and its views, and nowhere else.
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));

        foreach ($files as $file) {
            $contents = (string) file_get_contents($file->getPathname());
            file_put_contents($file->getPathname(), str_replace($pkg->routeNamePrefix(), $rewritePrefixTo, $contents));
        }
    }

    require $root . '/src/Http/Controllers/LicenseController.php';
    require $root . '/src/Providers/' . Str::studly($key) . 'PresetServiceProvider.php';
    registerGeneratedProvider($namespace . '\\Providers\\' . Str::studly($key) . 'PresetServiceProvider');

    return $root;
}

it('gives a newly generated Blade package vendor-scoped route names', function (): void {
    $root = bootPrefixedPresetPackage('blade', 'Acme\\PrefixBladeNew');

    expect(file_get_contents($root . '/config/license-verifier-blade.php'))
        ->toContain("'name' => 'laranail-license-verifier-ui.'");

    $router = app('router');

    foreach (['unlicensed', 'status', 'activate', 'deactivate', 'reminder.skip'] as $name) {
        expect($router->has('laranail-license-verifier-ui.' . $name))->toBeTrue("missing laranail-license-verifier-ui.{$name}")
            ->and($router->has('license-verifier.' . $name))->toBeFalse("bare license-verifier.{$name} registered");
    }

    $this->get('license/unlicensed')->assertOk()->assertSee('name="license_key"', false);
});

it('gives a newly generated Vue package vendor-scoped route names', function (): void {
    $root = bootPrefixedPresetPackage('vue', 'Acme\\PrefixVueNew');

    expect(file_get_contents($root . '/config/license-verifier-vue.php'))
        ->toContain("'name' => 'laranail-license-verifier-ui-vue.'")
        ->and(file_get_contents($root . '/resources/views/mount.blade.php'))
        ->toContain("route('laranail-license-verifier-ui-vue.status')");

    $router = app('router');

    foreach (['status', 'activate', 'deactivate'] as $name) {
        expect($router->has('laranail-license-verifier-ui-vue.' . $name))->toBeTrue()
            ->and($router->has('license-verifier-vue.' . $name))->toBeFalse();
    }
});

it('keeps a Blade package generated with the old prefix working unchanged', function (): void {
    bootPrefixedPresetPackage('blade', 'Acme\\PrefixBladeOld', rewritePrefixTo: 'license-verifier.');

    expect(app('router')->has('license-verifier.activate'))->toBeTrue()
        ->and(app('router')->has('laranail-license-verifier-ui.activate'))->toBeFalse();

    $this->get('license/unlicensed')->assertOk()->assertSee('name="license_key"', false);
    $this->postJson('license/activate', ['license_key' => 'DEV-KEY'])->assertOk();
});

it('keeps a Vue package generated with the old prefix working unchanged', function (): void {
    bootPrefixedPresetPackage('vue', 'Acme\\PrefixVueOld', rewritePrefixTo: 'license-verifier-vue.');

    expect(app('router')->has('license-verifier-vue.status'))->toBeTrue();

    $this->getJson('license/status')->assertOk();
});

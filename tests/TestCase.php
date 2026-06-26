<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Tests;

use Livewire\LivewireServiceProvider as LivewireFrameworkServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Simtabi\Laranail\Licence\Verifier\Presets\Blade\Providers\BladeServiceProvider;
use Simtabi\Laranail\Licence\Verifier\Presets\Filament\Providers\FilamentServiceProvider;
use Simtabi\Laranail\Licence\Verifier\Presets\Livewire\Providers\LivewireServiceProvider;
use Simtabi\Laranail\Licence\Verifier\Presets\Providers\LicenseVerifierUiServiceProvider;
use Simtabi\Laranail\Licence\Verifier\Presets\Vue\Providers\VueServiceProvider;
use Simtabi\Laranail\Licence\Verifier\Providers\LicenceVerifierServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LivewireFrameworkServiceProvider::class,
            LicenceVerifierServiceProvider::class,
            LicenseVerifierUiServiceProvider::class,
            BladeServiceProvider::class,
            LivewireServiceProvider::class,
            FilamentServiceProvider::class,
            VueServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        $app['config']->set('license-verifier.default', 'null');
        $app['config']->set('license-verifier.heartbeat.enabled', false);
    }
}

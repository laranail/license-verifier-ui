<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Blade\Providers;

use Illuminate\Support\Facades\Route;
use Override;
use Simtabi\Laranail\Package\Tools\Package;
use Simtabi\Laranail\Package\Tools\Providers\PackageServiceProvider;

final class BladePresetServiceProvider extends PackageServiceProvider
{
    #[Override]
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laranail/license-verifier-blade-preset')
            ->hasConfigFile('license-verifier-blade')
            ->hasViews('license-verifier-blade');
    }

    #[Override]
    public function packageBooted(): void
    {
        $this->registerRoutes();
        $this->registerPublishables();
    }

    private function registerRoutes(): void
    {
        if (! config('license-verifier-blade.routes.enabled', true)) {
            return;
        }

        Route::group([
            'prefix' => config('license-verifier-blade.routes.prefix', 'license'),
            'as' => config('license-verifier-blade.routes.name', 'license-verifier.'),
            'middleware' => config('license-verifier-blade.routes.middleware', ['web']),
        ], fn () => $this->loadRoutesFrom(__DIR__.'/../routes/web.php'));
    }

    private function registerPublishables(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../resources/js' => public_path('vendor/license-verifier-blade'),
        ], 'license-verifier-blade-assets');
    }
}

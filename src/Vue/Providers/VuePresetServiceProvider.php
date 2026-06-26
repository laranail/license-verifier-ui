<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Vue\Providers;

use Illuminate\Support\Facades\Route;
use Override;
use Simtabi\Laranail\Licence\Verifier\Presets\Vue\Http\Controllers\LicenseController;
use Simtabi\Laranail\Package\Tools\Package;
use Simtabi\Laranail\Package\Tools\Providers\PackageServiceProvider;

final class VuePresetServiceProvider extends PackageServiceProvider
{
    #[Override]
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laranail/license-verifier-vue-preset')
            ->hasConfigFile('license-verifier-vue')
            ->hasViews('license-verifier-vue');
    }

    #[Override]
    public function packageBooted(): void
    {
        if (config('license-verifier-vue.routes.enabled', true)) {
            Route::group([
                'prefix' => config('license-verifier-vue.routes.prefix', 'license'),
                'as' => 'license-verifier-vue.',
                'middleware' => config('license-verifier-vue.routes.middleware', ['web']),
            ], function (): void {
                Route::get('status', [LicenseController::class, 'status'])->name('status');
                Route::post('activate', [LicenseController::class, 'activate'])->name('activate');
                Route::post('deactivate', [LicenseController::class, 'deactivate'])->name('deactivate');
            });
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/js' => resource_path('js/vendor/license-verifier'),
            ], 'license-verifier-vue-assets');
        }
    }
}

<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Vue\Providers;

use Override;
use Illuminate\Support\Facades\Route;
use Simtabi\Laranail\Licence\Verifier\Presets\Providers\BasePresetServiceProvider;

/**
 * Family base for a generated Vue preset package: loads the JSON endpoints
 * consumed by the Vue component, gated by config.
 */
abstract class BaseVuePresetServiceProvider extends BasePresetServiceProvider
{
    #[Override]
    protected function bootPreset(): void
    {
        if (! config($this->configKey() . '.routes.enabled', true)) {
            return;
        }

        $routes = $this->packagePath('routes/web.php');

        if (! is_file($routes)) {
            return;
        }

        // A newly generated package sets `laranail-license-verifier-ui-vue.` in its own config.
        // This legacy default is kept so a package generated earlier keeps its route names.
        Route::group([
            'prefix'     => config($this->configKey() . '.routes.prefix', 'license'),
            'as'         => config($this->configKey() . '.routes.name', 'license-verifier-vue.'),
            'middleware' => config($this->configKey() . '.routes.middleware', ['web']),
        ], fn () => $this->loadRoutesFrom($routes));
    }
}

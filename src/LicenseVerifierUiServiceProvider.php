<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets;

use Illuminate\Support\ServiceProvider;
use Simtabi\Laranail\Licence\Verifier\Presets\Console\InstallPresetsCommand;
use Simtabi\Laranail\Licence\Verifier\Presets\Support\Preset;
use Simtabi\Laranail\Licence\Verifier\Presets\Support\PresetRegistry;

/**
 * Umbrella provider for the license-verifier UI presets (Blade, Filament,
 * Livewire, Vue). This is the only auto-discovered provider; it registers each
 * preset's own service provider conditionally based on framework availability
 * and the `license-verifier-ui.presets.*` config toggles.
 *
 * It is a plain ServiceProvider (not a package-tools PackageServiceProvider) so
 * the umbrella config lands at the flat key `license-verifier-ui` rather than a
 * vendor-namespaced one.
 */
final class LicenseVerifierUiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/license-verifier-ui.php', 'license-verifier-ui');

        foreach (PresetRegistry::all() as $preset) {
            if ($this->presetActive($preset)) {
                $this->app->register($preset->provider);
            }
        }
    }

    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/license-verifier-ui.php' => config_path('license-verifier-ui.php'),
        ], 'license-verifier-ui-config');

        $this->commands([InstallPresetsCommand::class]);
    }

    /**
     * Auto-activation with config override:
     *  - enabled === null  → auto: active when the framework is present.
     *  - enabled === false → hard-disabled, even if the framework is present.
     *  - enabled truthy    → active, but still requires the framework.
     */
    private function presetActive(Preset $preset): bool
    {
        $enabled = config("license-verifier-ui.presets.{$preset->key}.enabled");

        if ($enabled === null) {
            return $preset->frameworkAvailable();
        }

        return (bool) $enabled && $preset->frameworkAvailable();
    }
}

<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Override;
use Simtabi\Laranail\Licence\Verifier\Presets\Console\InstallPresetCommand;
use Simtabi\Laranail\Licence\Verifier\Presets\Console\ListPresetsCommand;
use Simtabi\Laranail\Licence\Verifier\Presets\Console\UninstallPresetCommand;
use Simtabi\Laranail\Licence\Verifier\Presets\Generators\PresetPackageGenerator;
use Simtabi\Laranail\Licence\Verifier\Presets\Generators\StubRenderer;
use Simtabi\Laranail\Licence\Verifier\Presets\Presets\PresetRegistry;
use Simtabi\Laranail\Licence\Verifier\Presets\Rendering\FieldRenderer;

/**
 * Core service provider. Registers the generator/registry and the install,
 * uninstall and list commands. It does NOT register any UI — presets are
 * generated into owned packages that auto-discover their own providers.
 */
final class LicenseVerifierUiServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->mergeConfigFrom($this->configPath(), 'license-verifier-ui');

        $this->app->singleton(PresetRegistry::class);
        $this->app->singleton(StubRenderer::class);
        $this->app->singleton(FieldRenderer::class);

        $this->app->singleton(PresetPackageGenerator::class, fn ($app): PresetPackageGenerator => new PresetPackageGenerator(
            $app->make(StubRenderer::class),
            $app->make(Filesystem::class),
        ));
    }

    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            $this->configPath() => config_path('license-verifier-ui.php'),
        ], 'license-verifier-ui-config');

        $this->commands([
            InstallPresetCommand::class,
            UninstallPresetCommand::class,
            ListPresetsCommand::class,
        ]);
    }

    /** Absolute path to the package's config file (provider lives in src/Providers). */
    private function configPath(): string
    {
        return \dirname(__DIR__, 2).'/config/license-verifier-ui.php';
    }
}

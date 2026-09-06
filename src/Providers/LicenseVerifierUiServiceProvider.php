<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Providers;

use Override;
use Composer\InstalledVersions;
use Illuminate\Filesystem\Filesystem;
use Simtabi\Laranail\Package\Tools\Package;
use Simtabi\Laranail\Licence\Verifier\Presets\Doctor\Checks;
use Simtabi\Laranail\Licence\Verifier\Presets\Console\DoctorCommand;
use Simtabi\Laranail\Package\Tools\Providers\PackageServiceProvider;
use Simtabi\Laranail\Licence\Verifier\Presets\Presets\PresetRegistry;
use Simtabi\Laranail\Licence\Verifier\Presets\Generators\StubRenderer;
use Simtabi\Laranail\Licence\Verifier\Presets\Rendering\FieldRenderer;
use Simtabi\Laranail\Licence\Verifier\Presets\Console\ListPresetsCommand;
use Simtabi\Laranail\Licence\Verifier\Presets\Console\InstallPresetCommand;
use Simtabi\Laranail\Licence\Verifier\Presets\Console\UninstallPresetCommand;
use Simtabi\Laranail\Package\Tools\Support\Definitions\AboutSectionDefinition;
use Simtabi\Laranail\Licence\Verifier\Presets\Generators\PresetPackageGenerator;

/**
 * Core service provider. Registers the generator/registry and the install,
 * uninstall, list and doctor commands. It does NOT register any UI — presets are
 * generated into owned packages that auto-discover their own providers.
 */
final class LicenseVerifierUiServiceProvider extends PackageServiceProvider
{
    #[Override]
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laranail/license-verifier-ui')
            ->hasConfigFile('license-verifier-ui')
            ->withoutConfigNamespacing()
            ->hasCommands(
                InstallPresetCommand::class,
                UninstallPresetCommand::class,
                ListPresetsCommand::class,
                DoctorCommand::class,
            )
            ->hasDoctorChecks(Checks::all())
            ->hasAboutSection(
                AboutSectionDefinition::make('License Verifier UI')
                    ->field('Version', fn (): string => (string) InstalledVersions::getPrettyVersion('laranail/license-verifier-ui')),
            );
    }

    #[Override]
    public function packageRegistered(): void
    {
        $this->app->singleton(PresetRegistry::class);
        $this->app->singleton(StubRenderer::class);
        $this->app->singleton(FieldRenderer::class);

        $this->app->singleton(PresetPackageGenerator::class, fn ($app): PresetPackageGenerator => new PresetPackageGenerator(
            $app->make(StubRenderer::class),
            $app->make(Filesystem::class),
        ));
    }
}

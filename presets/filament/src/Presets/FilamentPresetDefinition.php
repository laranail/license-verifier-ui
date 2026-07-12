<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Filament\Presets;

use Simtabi\Laranail\Licence\Verifier\Presets\Presets\PresetDefinition;
use Simtabi\Laranail\Licence\Verifier\Presets\Themes\Theme;

final class FilamentPresetDefinition
{
    public static function make(string $stubsPath): PresetDefinition
    {
        return new PresetDefinition(
            key: 'filament',
            label: 'Filament (page + widget plugin)',
            supportedThemes: [Theme::FILAMENT],
            defaultTheme: Theme::FILAMENT,
            stubsPath: $stubsPath,
            composerRequire: 'laranail/license-verifier-ui-filament',
            frameworkRequire: 'filament/filament:^4.0 || ^3.2',
            fileMap: [
                'scaffold/Provider.php.stub' => 'src/Providers/$PROVIDER_CLASS$.php',
                'scaffold/Filament/Pages/LicensePage.php.stub' => 'src/Filament/Pages/LicensePage.php',
                'scaffold/Filament/Widgets/LicenseStatusWidget.php.stub' => 'src/Filament/Widgets/LicenseStatusWidget.php',
                'scaffold/Plugin/LicenseVerifierPlugin.php.stub' => 'src/LicenseVerifierPlugin.php',
                'scaffold/config.php.stub' => 'config/$CONFIG_KEY$.php',
            ],
        );
    }
}

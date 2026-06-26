<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Vue\Presets;

use Simtabi\Laranail\Licence\Verifier\Presets\Presets\PresetDefinition;
use Simtabi\Laranail\Licence\Verifier\Presets\Themes\Theme;

final class VuePresetDefinition
{
    public static function make(string $stubsPath): PresetDefinition
    {
        return new PresetDefinition(
            key: 'vue',
            label: 'Vue 3 SFC + JSON endpoints',
            supportedThemes: [Theme::TAILWIND, Theme::BOOTSTRAP, Theme::UNSTYLED, Theme::CUSTOM],
            defaultTheme: Theme::TAILWIND,
            stubsPath: $stubsPath,
            composerRequire: 'laranail/license-verifier-ui-vue',
            frameworkRequire: null,
            fileMap: [
                'scaffold/Provider.php.stub' => 'src/Providers/$PROVIDER_CLASS$.php',
                'scaffold/Http/Controllers/LicenseController.php.stub' => 'src/Http/Controllers/LicenseController.php',
                'scaffold/routes.web.php.stub' => 'routes/web.php',
                'scaffold/config.php.stub' => 'config/$CONFIG_KEY$.php',
                'scaffold/package.json.stub' => 'package.json',
            ],
        );
    }
}

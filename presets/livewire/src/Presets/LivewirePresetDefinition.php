<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Livewire\Presets;

use Simtabi\Laranail\Licence\Verifier\Presets\Presets\PresetDefinition;
use Simtabi\Laranail\Licence\Verifier\Presets\Themes\Theme;

final class LivewirePresetDefinition
{
    public static function make(string $stubsPath): PresetDefinition
    {
        return new PresetDefinition(
            key: 'livewire',
            label: 'Livewire components',
            supportedThemes: [Theme::TAILWIND, Theme::BOOTSTRAP, Theme::UNSTYLED, Theme::CUSTOM],
            defaultTheme: Theme::TAILWIND,
            stubsPath: $stubsPath,
            composerRequire: 'laranail/license-verifier-ui-livewire',
            frameworkRequire: 'livewire/livewire:^3.5',
            fileMap: [
                'scaffold/Provider.php.stub' => 'src/Providers/$PROVIDER_CLASS$.php',
                'scaffold/Components/ActivationForm.php.stub' => 'src/Components/ActivationForm.php',
                'scaffold/Components/StatusWidget.php.stub' => 'src/Components/StatusWidget.php',
                'scaffold/config.php.stub' => 'config/$CONFIG_KEY$.php',
            ],
        );
    }
}

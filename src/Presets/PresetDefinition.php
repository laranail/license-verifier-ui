<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Presets;

/**
 * Immutable description of a UI preset, contributed into the {@see PresetRegistry}
 * by each installed preset package. Tells the generator everything it needs to
 * scaffold an owned, extending package for this preset.
 */
final class PresetDefinition
{
    /**
     * @param  string  $key  Stable preset key (blade|livewire|filament|vue)
     * @param  string  $label  Human label shown in the install prompt
     * @param  list<string>  $supportedThemes  Theme keys this preset can render
     * @param  string  $defaultTheme  The theme pre-selected in the prompt
     * @param  string  $stubsPath  Absolute path to the preset package's stubs/ dir
     * @param  string  $composerRequire  Base package the generated one requires (laranail/license-verifier-ui-<preset>)
     * @param  string|null  $frameworkRequire  Optional extra require for the generated package (e.g. "livewire/livewire:^3.5")
     * @param  array<string, string>  $fileMap  scaffold stub (relative to stubsPath) => output path (relative to package root); tokens allowed in the output path
     */
    public function __construct(
        public string $key,
        public string $label,
        public array $supportedThemes,
        public string $defaultTheme,
        public string $stubsPath,
        public string $composerRequire,
        public ?string $frameworkRequire,
        public array $fileMap,
    ) {}

    public function supportsTheme(string $theme): bool
    {
        return in_array($theme, $this->supportedThemes, true);
    }
}

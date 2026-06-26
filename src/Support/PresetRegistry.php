<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Support;

use Simtabi\Laranail\Licence\Verifier\Presets\Blade\Providers\BladePresetServiceProvider;
use Simtabi\Laranail\Licence\Verifier\Presets\Filament\Providers\FilamentPresetServiceProvider;
use Simtabi\Laranail\Licence\Verifier\Presets\Livewire\Providers\LivewirePresetServiceProvider;
use Simtabi\Laranail\Licence\Verifier\Presets\Vue\Providers\VuePresetServiceProvider;

/**
 * Single source of truth for the UI presets bundled in this package.
 *
 * The publish tags are exactly what package-tools emits per sub-provider:
 * config/view tags are `laranail::{package-name}-{config|views}` (derived from
 * each sub-provider's unchanged `->name()`), and the asset tags are the
 * historical literal strings each provider registers by hand. Keep these in
 * sync if a sub-provider's `->name()` ever changes.
 */
final class PresetRegistry
{
    /**
     * @return list<Preset>
     */
    public static function all(): array
    {
        return [
            new Preset(
                key: 'blade',
                label: 'Blade + vanilla JS',
                frameworkClass: null,
                provider: BladePresetServiceProvider::class,
                publishTags: [
                    'laranail::license-verifier-blade-preset-config',
                    'laranail::license-verifier-blade-preset-views',
                    'license-verifier-blade-assets',
                ],
            ),
            new Preset(
                key: 'filament',
                label: 'Filament (page + widget plugin)',
                frameworkClass: \Filament\Panel::class,
                provider: FilamentPresetServiceProvider::class,
                publishTags: [
                    'laranail::license-verifier-filament-preset-config',
                    'laranail::license-verifier-filament-preset-views',
                ],
            ),
            new Preset(
                key: 'livewire',
                label: 'Livewire components',
                frameworkClass: \Livewire\Livewire::class,
                provider: LivewirePresetServiceProvider::class,
                publishTags: [
                    'laranail::license-verifier-livewire-preset-config',
                    'laranail::license-verifier-livewire-preset-views',
                ],
            ),
            new Preset(
                key: 'vue',
                label: 'Vue 3 SFC + JSON endpoints',
                frameworkClass: null,
                provider: VuePresetServiceProvider::class,
                publishTags: [
                    'laranail::license-verifier-vue-preset-config',
                    'laranail::license-verifier-vue-preset-views',
                    'license-verifier-vue-assets',
                ],
            ),
        ];
    }

    public static function get(string $key): ?Preset
    {
        foreach (self::all() as $preset) {
            if ($preset->key === $key) {
                return $preset;
            }
        }

        return null;
    }

    /**
     * Presets whose framework is present (or framework-free) — the set a user
     * can meaningfully install in the current app.
     *
     * @return list<Preset>
     */
    public static function installable(): array
    {
        return array_values(array_filter(
            self::all(),
            static fn (Preset $preset): bool => $preset->frameworkAvailable(),
        ));
    }
}

<?php

declare(strict_types=1);

use Simtabi\Laranail\Licence\Verifier\Presets\Support\PresetRegistry;

it('registers the four presets in a stable order', function () {
    $keys = array_map(static fn ($preset) => $preset->key, PresetRegistry::all());

    expect($keys)->toBe(['blade', 'filament', 'livewire', 'vue']);
});

it('preserves the historical asset publish tags', function () {
    expect(PresetRegistry::get('blade')->publishTags)->toContain('license-verifier-blade-assets')
        ->and(PresetRegistry::get('vue')->publishTags)->toContain('license-verifier-vue-assets');
});

it('exposes the package-tools config and view publish tags', function () {
    expect(PresetRegistry::get('filament')->publishTags)->toContain('laranail::license-verifier-filament-preset-config')
        ->and(PresetRegistry::get('livewire')->publishTags)->toContain('laranail::license-verifier-livewire-preset-views');
});

it('treats blade and vue as framework-free and gates filament and livewire', function () {
    expect(PresetRegistry::get('blade')->frameworkClass)->toBeNull()
        ->and(PresetRegistry::get('vue')->frameworkClass)->toBeNull()
        ->and(PresetRegistry::get('filament')->frameworkClass)->toBe(\Filament\Panel::class)
        ->and(PresetRegistry::get('livewire')->frameworkClass)->toBe(\Livewire\Livewire::class);
});

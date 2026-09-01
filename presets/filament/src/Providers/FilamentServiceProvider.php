<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Filament\Providers;

use Illuminate\Support\ServiceProvider;
use Simtabi\Laranail\Licence\Verifier\Presets\Filament\Presets\FilamentPresetDefinition;
use Simtabi\Laranail\Licence\Verifier\Presets\Presets\Contracts\PresetContributor;
use Simtabi\Laranail\Licence\Verifier\Presets\Presets\PresetDefinition;
use Simtabi\Laranail\Licence\Verifier\Presets\Presets\PresetRegistry;

final class FilamentServiceProvider extends ServiceProvider implements PresetContributor
{
    public function boot(): void
    {
        $this->app->make(PresetRegistry::class)->register($this->presetDefinition());
    }

    public function presetDefinition(): PresetDefinition
    {
        return FilamentPresetDefinition::make(dirname(__DIR__, 2).'/stubs');
    }
}

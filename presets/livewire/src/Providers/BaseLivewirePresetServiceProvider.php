<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Livewire\Providers;

use Livewire\Livewire;
use Override;
use Simtabi\Laranail\Licence\Verifier\Presets\Providers\BasePresetServiceProvider;

/**
 * Family base for a generated Livewire preset package: registers the package's
 * Livewire components (alias => class) on top of the core's config/view setup.
 */
abstract class BaseLivewirePresetServiceProvider extends BasePresetServiceProvider
{
    /**
     * @return array<string, class-string> component alias => component class
     */
    abstract protected function components(): array;

    #[Override]
    protected function bootPreset(): void
    {
        if (! class_exists(Livewire::class)) {
            return;
        }

        foreach ($this->components() as $alias => $class) {
            Livewire::component($alias, $class);
        }
    }
}

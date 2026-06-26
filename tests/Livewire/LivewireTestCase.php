<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Tests\Livewire;

use Livewire\LivewireServiceProvider;
use Simtabi\Laranail\Licence\Verifier\Presets\Livewire\Providers\LivewirePresetServiceProvider;
use Simtabi\Laranail\Licence\Verifier\Presets\Tests\TestCase;

class LivewireTestCase extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            ...parent::getPackageProviders($app),
            LivewirePresetServiceProvider::class,
        ];
    }
}

<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Tests\Filament;

use Simtabi\Laranail\Licence\Verifier\Presets\Filament\Providers\FilamentPresetServiceProvider;
use Simtabi\Laranail\Licence\Verifier\Presets\Tests\TestCase;

class FilamentTestCase extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), FilamentPresetServiceProvider::class];
    }
}

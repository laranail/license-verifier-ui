<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Tests\Blade;

use Simtabi\Laranail\Licence\Verifier\Presets\Blade\Providers\BladePresetServiceProvider;
use Simtabi\Laranail\Licence\Verifier\Presets\Tests\TestCase;

class BladeTestCase extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), BladePresetServiceProvider::class];
    }
}

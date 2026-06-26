<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Tests\Vue;

use Simtabi\Laranail\Licence\Verifier\Presets\Tests\TestCase;
use Simtabi\Laranail\Licence\Verifier\Presets\Vue\Providers\VuePresetServiceProvider;

class VueTestCase extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), VuePresetServiceProvider::class];
    }
}

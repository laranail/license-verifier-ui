<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Simtabi\Laranail\Licence\Verifier\Providers\LicenceVerifierServiceProvider;

/**
 * Shared test base for every preset suite. Subclasses add their own preset
 * service provider (and any framework provider) via getPackageProviders().
 */
abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [LicenceVerifierServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('license-verifier.storage.driver', 'database');
        $app['config']->set('license-verifier.storage.path', sys_get_temp_dir().'/lv-'.uniqid());
        $app['config']->set('license-verifier.heartbeat.enabled', false);
    }

    protected function defineDatabaseMigrations(): void
    {
        (include __DIR__.'/../../verifier/database/migrations/create_license_verifier_table.php.stub')->up();
    }
}

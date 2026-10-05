<?php

declare(strict_types=1);
use Simtabi\Laranail\Licence\Verifier\Presets\Tests\TestCase;
use Simtabi\Laranail\Licence\Verifier\Presets\Tests\FilamentViewTestCase;

uses(TestCase::class)->in('Feature', 'Unit', 'Generation', 'Validation');
uses(FilamentViewTestCase::class)->in('Filament');

/**
 * Register a generated preset provider after the application has booted, the
 * way these end-to-end tests have to, and refresh the route name lookups.
 *
 * A real application registers the provider before boot, and Laravel refreshes
 * the name lookups once every provider has booted. A provider registered later
 * adds routes whose names were set after `RouteCollection::add()`, so
 * `route('license-verifier.activate')` misses them until the lookups are
 * refreshed. Testbench refreshes them only when `url` is first resolved, so
 * whether these tests passed depended on nothing having resolved `url` before
 * this call. With the current dependency set something does, and seven tests
 * went red on a fresh install.
 */
function registerGeneratedProvider(string $provider): void
{
    app()->register($provider);
    app('router')->getRoutes()->refreshNameLookups();
}

<?php

declare(strict_types=1);

use Simtabi\Laranail\Licence\Verifier\Contracts\Driver;
use Simtabi\Laranail\Licence\Verifier\Drivers\DriverManager;
use Simtabi\Laranail\Licence\Verifier\Presets\Filament\Filament\Pages\BaseLicensePage;

/*
 * The licence page tells the browser the licence changed. The event carries the vendor-scoped name,
 * and the bare `license-updated` is still sent beside it until the deprecation ends, so a listener
 * written against either name keeps working.
 */
it('dispatches the scoped license-updated event, and the deprecated bare one beside it', function (): void {
    $driver = Mockery::mock(Driver::class);
    $driver->shouldReceive('deactivate')->once();
    $manager = Mockery::mock(DriverManager::class);
    $manager->shouldReceive('active')->andReturn($driver);
    app()->instance(DriverManager::class, $manager);

    $page = new class extends BaseLicensePage
    {
        /** @var list<array{string, array<string, mixed>}> */
        public array $sent = [];

        public function dispatch($event, ...$params)
        {
            $this->sent[] = [$event, $params];

            return null;
        }
    };

    $page->deactivate();

    expect($page->sent)->toBe([
        ['laranail-license-verifier-ui:license-updated', ['valid' => false]],
        ['license-updated', ['valid' => false]],
    ]);
});

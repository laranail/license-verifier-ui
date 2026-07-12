<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Events;

use Illuminate\Foundation\Events\Dispatchable;

/**
 * Dispatched after a generated preset package is uninstalled.
 */
final readonly class PresetUninstalled
{
    use Dispatchable;

    public function __construct(
        public string $package,
        public bool $filesDeleted = false,
    ) {}
}

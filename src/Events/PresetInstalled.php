<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Events;

use Illuminate\Foundation\Events\Dispatchable;

/**
 * Dispatched after a preset package is successfully generated/installed.
 */
final readonly class PresetInstalled
{
    use Dispatchable;

    public function __construct(
        public string $preset,
        public string $namespace,
        public string $path,
    ) {}
}

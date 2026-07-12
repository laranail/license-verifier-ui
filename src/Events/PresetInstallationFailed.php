<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Events;

use Illuminate\Foundation\Events\Dispatchable;

/**
 * Dispatched when preset generation or composer registration fails.
 */
final readonly class PresetInstallationFailed
{
    use Dispatchable;

    public function __construct(
        public string $preset,
        public string $reason,
    ) {}
}

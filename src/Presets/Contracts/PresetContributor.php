<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Presets\Contracts;

use Simtabi\Laranail\Licence\Verifier\Presets\Presets\PresetDefinition;

/**
 * Implemented by a preset package's service provider so the core can collect
 * its {@see PresetDefinition}. (Providers may also register directly against the
 * PresetRegistry singleton; this contract documents the expectation.)
 */
interface PresetContributor
{
    public function presetDefinition(): PresetDefinition;
}

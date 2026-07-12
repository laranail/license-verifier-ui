<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Doctor;

use Simtabi\Laranail\Licence\Verifier\Presets\Presets\PresetRegistry;
use Simtabi\Laranail\Package\Tools\Services\Doctor\DoctorCheck;
use Simtabi\Laranail\Package\Tools\Services\Doctor\DoctorResult;

/**
 * At least one preset package should contribute a definition before installing.
 */
final class PresetsRegisteredCheck implements DoctorCheck
{
    public function name(): string
    {
        return 'license-verifier-ui:presets';
    }

    public function description(): string
    {
        return 'At least one preset is registered';
    }

    public function run(): DoctorResult
    {
        $count = count(app(PresetRegistry::class)->all());

        return $count > 0
            ? DoctorResult::pass("{$count} preset(s) registered.")
            : DoctorResult::warn('No presets are registered; install a preset base package first.');
    }
}

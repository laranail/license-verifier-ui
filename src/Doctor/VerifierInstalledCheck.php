<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Doctor;

use Simtabi\Laranail\Licence\Verifier\LicenseManager;
use Simtabi\Laranail\Package\Tools\Services\Doctor\DoctorCheck;
use Simtabi\Laranail\Package\Tools\Services\Doctor\DoctorResult;

/**
 * The presets exist to surface the verifier, so it must be installed.
 */
final class VerifierInstalledCheck implements DoctorCheck
{
    public function name(): string
    {
        return 'license-verifier-ui:verifier';
    }

    public function description(): string
    {
        return 'laranail/license-verifier is installed';
    }

    public function run(): DoctorResult
    {
        return class_exists(LicenseManager::class)
            ? DoctorResult::pass('laranail/license-verifier is installed.')
            : DoctorResult::fail('laranail/license-verifier is required but not installed.');
    }
}

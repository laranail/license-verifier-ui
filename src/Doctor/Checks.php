<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Doctor;

use Simtabi\Laranail\Licence\Verifier\LicenseManager;
use Simtabi\Laranail\Package\Tools\Services\Doctor\Checks\SoftDependencyCheck;
use Simtabi\Laranail\Package\Tools\Services\Doctor\DoctorCheck;

/**
 * The canonical license-verifier-ui health checks — one list reused by the
 * service provider (unified doctor) and the doctor command.
 */
final class Checks
{
    /**
     * @return list<DoctorCheck|class-string<DoctorCheck>>
     */
    public static function all(): array
    {
        return [
            new SoftDependencyCheck(
                LicenseManager::class,
                'laranail/license-verifier',
                name: 'license-verifier-ui:verifier',
                description: 'laranail/license-verifier is installed',
            ),
            PresetsRegisteredCheck::class,
            DefaultThemeValidCheck::class,
        ];
    }
}

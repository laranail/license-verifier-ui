<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Doctor;

use Simtabi\Laranail\Licence\Verifier\Presets\Themes\Theme;
use Simtabi\Laranail\Package\Tools\Services\Doctor\DoctorCheck;
use Simtabi\Laranail\Package\Tools\Services\Doctor\DoctorResult;

/**
 * The configured default theme must be one the generator supports.
 */
final class DefaultThemeValidCheck implements DoctorCheck
{
    public function name(): string
    {
        return 'license-verifier-ui:default-theme';
    }

    public function description(): string
    {
        return 'The configured default theme is supported';
    }

    public function run(): DoctorResult
    {
        $theme = (string) config('license-verifier-ui.default_theme', 'tailwind');
        $supported = array_keys(Theme::labels());

        return in_array($theme, $supported, true)
            ? DoctorResult::pass("Default theme \"{$theme}\" is supported.")
            : DoctorResult::fail("Unsupported default theme \"{$theme}\".", ['supported' => $supported]);
    }
}

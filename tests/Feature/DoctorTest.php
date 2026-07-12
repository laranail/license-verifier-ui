<?php

declare(strict_types=1);

use Simtabi\Laranail\Licence\Verifier\Presets\Doctor\DefaultThemeValidCheck;
use Simtabi\Laranail\Package\Tools\Services\Doctor\DoctorStatus;

it('fails the theme check for an unsupported theme', function (): void {
    config()->set('license-verifier-ui.default_theme', 'neon');

    expect((new DefaultThemeValidCheck)->run()->status)->toBe(DoctorStatus::Fail);
});

it('passes the theme check for a supported theme', function (): void {
    config()->set('license-verifier-ui.default_theme', 'tailwind');

    expect((new DefaultThemeValidCheck)->run()->status)->toBe(DoctorStatus::Pass);
});

it('runs the doctor command', function (): void {
    $this->artisan('laranail::license-verifier-ui.doctor --json')->run();

    expect(true)->toBeTrue();
});

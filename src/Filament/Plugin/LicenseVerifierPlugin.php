<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Simtabi\Laranail\Licence\Verifier\Presets\Filament\Filament\Pages\LicensePage;
use Simtabi\Laranail\Licence\Verifier\Presets\Filament\Filament\Widgets\LicenseStatusWidget;

/**
 * Register on a Filament panel:  ->plugin(LicenseVerifierPlugin::make())
 */
final class LicenseVerifierPlugin implements Plugin
{
    public function getId(): string
    {
        return 'license-verifier';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->pages([LicensePage::class])
            ->widgets([LicenseStatusWidget::class]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return new self();
    }
}

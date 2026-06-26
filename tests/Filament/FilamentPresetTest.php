<?php

declare(strict_types=1);

use Simtabi\Laranail\Licence\Verifier\Presets\Filament\Filament\Pages\LicensePage;
use Simtabi\Laranail\Licence\Verifier\Presets\Filament\Filament\Widgets\LicenseStatusWidget;
use Simtabi\Laranail\Licence\Verifier\Presets\Filament\Plugin\LicenseVerifierPlugin;

it('exposes a filament plugin with a stable id', function () {
    expect(LicenseVerifierPlugin::make()->getId())->toBe('license-verifier');
});

it('ships a page and widget that load against the installed filament', function () {
    expect(class_exists(LicensePage::class))->toBeTrue()
        ->and(class_exists(LicenseStatusWidget::class))->toBeTrue()
        ->and(is_subclass_of(LicensePage::class, \Filament\Pages\Page::class))->toBeTrue()
        ->and(is_subclass_of(LicenseStatusWidget::class, \Filament\Widgets\Widget::class))->toBeTrue();
});

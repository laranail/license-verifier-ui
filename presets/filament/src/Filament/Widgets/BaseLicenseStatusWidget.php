<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Filament\Filament\Widgets;

use Filament\Widgets\Widget;
use Override;
use Simtabi\Laranail\Licence\Verifier\Drivers\DriverManager;

/**
 * Base Filament license status widget. The generated widget subclasses this and
 * sets its `$view`.
 */
abstract class BaseLicenseStatusWidget extends Widget
{
    protected int|string|array $columnSpan = 'full';

    /**
     * @return array<string, mixed>
     */
    #[Override]
    protected function getViewData(): array
    {
        $result = app(DriverManager::class)->active()->verify();

        return [
            'label' => $result->status->label(),
            'valid' => $result->isUsable(),
        ];
    }
}

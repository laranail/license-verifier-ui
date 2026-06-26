<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Filament\Filament\Widgets;

use Filament\Widgets\Widget;
use Simtabi\Laranail\Licence\Verifier\Drivers\DriverManager;

class LicenseStatusWidget extends Widget
{
    protected string $view = 'license-verifier-filament::widgets.status';

    protected int|string|array $columnSpan = 'full';

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $result = app(DriverManager::class)->active()->verify();

        return [
            'label' => $result->status->label(),
            'valid' => $result->isUsable(),
        ];
    }
}

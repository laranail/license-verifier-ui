<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Livewire\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Simtabi\Laranail\Licence\Verifier\Drivers\DriverManager;

class StatusWidget extends Component
{
    public function render(DriverManager $drivers): View
    {
        $result = $drivers->active()->verify();

        return view('license-verifier-livewire::livewire.status-widget', [
            'status' => $result->status,
            'valid' => $result->isUsable(),
        ]);
    }
}

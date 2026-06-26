<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Livewire\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Simtabi\Laranail\Licence\Verifier\Drivers\DriverManager;

/**
 * Base Livewire status widget. The generated component subclasses this and
 * supplies its view namespace.
 */
abstract class BaseStatusWidget extends Component
{
    abstract protected function viewNamespace(): string;

    public function render(DriverManager $drivers): View
    {
        $result = $drivers->active()->verify();

        return view($this->viewNamespace().'::livewire.status-widget', [
            'status' => $result->status->label(),
            'valid' => $result->isUsable(),
        ]);
    }
}

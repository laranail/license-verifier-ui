<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Livewire\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Simtabi\Laranail\Licence\Verifier\Drivers\DriverManager;
use Simtabi\Laranail\Licence\Verifier\ValueObjects\LicenseRequest;

class ActivationForm extends Component
{
    public string $licenseKey = '';

    public ?string $client = null;

    public bool $agreement = false;

    public ?string $message = null;

    public bool $activated = false;

    public function activate(DriverManager $drivers): void
    {
        $this->validate(['licenseKey' => ['required', 'string']]);

        $result = $drivers->active()->activate(new LicenseRequest(
            key: $this->licenseKey,
            client: $this->client,
            metadata: ['agreement' => $this->agreement],
        ));

        $this->activated = $result->isUsable();
        $this->message = $result->isUsable()
            ? __('license-verifier::license-verifier.activated_successfully')
            : ($result->message ?? __('license-verifier::license-verifier.invalid_key'));
    }

    public function render(DriverManager $drivers): View
    {
        return view('license-verifier-livewire::livewire.activation-form', [
            'fields' => $drivers->active()->activationFields(),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Livewire\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Simtabi\Laranail\Licence\Verifier\Drivers\DriverManager;
use Simtabi\Laranail\Licence\Verifier\Presets\Rendering\FieldRenderer;
use Simtabi\Laranail\Licence\Verifier\ValueObjects\LicenseRequest;

/**
 * Base Livewire activation form. The generated component subclasses this and
 * supplies its view namespace.
 */
abstract class BaseActivationForm extends Component
{
    public string $licenseKey = '';

    public ?string $client = null;

    public bool $agreement = false;

    public ?string $message = null;

    public bool $activated = false;

    abstract protected function viewNamespace(): string;

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

    public function render(DriverManager $drivers, FieldRenderer $fields): View
    {
        return view($this->viewNamespace().'::livewire.activation-form', [
            'fields' => $fields->normalize($drivers->active()->activationFields()),
        ]);
    }
}

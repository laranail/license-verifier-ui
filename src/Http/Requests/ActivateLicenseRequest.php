<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Simtabi\Laranail\Licence\Verifier\Drivers\DriverManager;

/**
 * Shared, driver-aware activation request. Validation rules are derived from the
 * active driver's activationFields() schema. Authorization is handled by the
 * controller (which knows its own config key), so this always authorizes.
 */
final class ActivateLicenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        $rules = [];

        foreach (app(DriverManager::class)->active()->activationFields() as $field) {
            $name = (string) ($field['name'] ?? '');

            if ($name === '') {
                continue;
            }

            $rules[$name] = [
                ($field['required'] ?? false) ? 'required' : 'nullable',
                ($field['type'] ?? 'text') === 'checkbox' ? 'accepted' : 'string',
            ];
        }

        $rules['license_key'] ??= ['required', 'string'];

        return $rules;
    }
}

<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Blade\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Simtabi\Laranail\Licence\Verifier\Drivers\DriverManager;

/**
 * Validates activation input dynamically from the active driver's
 * activationFields() schema.
 */
final class ActivateLicenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        $permission = config('license-verifier-blade.permission');

        if ($permission === null) {
            return true;
        }

        return $this->user()?->can($permission) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [];

        foreach (app(DriverManager::class)->active()->activationFields() as $field) {
            $name = (string) ($field['name'] ?? '');

            if ($name === '') {
                continue;
            }

            $fieldRules = [($field['required'] ?? false) ? 'required' : 'nullable'];
            $fieldRules[] = ($field['type'] ?? 'text') === 'checkbox' ? 'accepted' : 'string';

            $rules[$name] = $fieldRules;
        }

        // Ensure a license_key rule always exists.
        $rules['license_key'] ??= ['required', 'string'];

        return $rules;
    }
}

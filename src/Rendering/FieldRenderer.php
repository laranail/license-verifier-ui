<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Rendering;

/**
 * Normalises the active driver's `activationFields()` into a predictable shape
 * for every themed view to render against, so themes never special-case the
 * driver. Shared by all presets.
 */
final class FieldRenderer
{
    /**
     * @param  array<int, array<string, mixed>>  $fields
     * @return list<array{name: string, label: string, type: string, required: bool, placeholder: string}>
     */
    public function normalize(array $fields): array
    {
        $normalized = [];

        foreach ($fields as $field) {
            $name = (string) ($field['name'] ?? '');

            if ($name === '') {
                continue;
            }

            $normalized[] = [
                'name' => $name,
                'label' => (string) ($field['label'] ?? ucfirst(str_replace('_', ' ', $name))),
                'type' => (string) ($field['type'] ?? 'text'),
                'required' => (bool) ($field['required'] ?? false),
                'placeholder' => (string) ($field['placeholder'] ?? ''),
            ];
        }

        if ($normalized === []) {
            $normalized[] = [
                'name' => 'license_key',
                'label' => __('license-verifier::license-verifier.license_key'),
                'type' => 'text',
                'required' => true,
                'placeholder' => '',
            ];
        }

        return $normalized;
    }
}

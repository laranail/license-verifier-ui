<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Vue\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Simtabi\Laranail\Licence\Verifier\Drivers\DriverManager;
use Simtabi\Laranail\Licence\Verifier\ValueObjects\LicenseRequest;

/**
 * JSON endpoints consumed by the Vue LicenseForm component.
 */
final class LicenseController extends Controller
{
    public function __construct(private readonly DriverManager $drivers) {}

    public function status(): JsonResponse
    {
        $result = $this->drivers->active()->verify();

        return response()->json([
            'data' => $result->toArray(),
            'valid' => $result->isUsable(),
            'fields' => $this->drivers->active()->activationFields(),
        ], $result->isUsable() ? 200 : 422);
    }

    public function activate(Request $request): JsonResponse
    {
        $this->authorizeManagement($request);

        $validated = $request->validate(['license_key' => ['required', 'string'], 'client' => ['nullable', 'string']]);

        $result = $this->drivers->active()->activate(new LicenseRequest(
            key: (string) $validated['license_key'],
            client: $validated['client'] ?? null,
        ));

        return response()->json([
            'message' => $result->isUsable()
                ? __('license-verifier::license-verifier.activated_successfully')
                : ($result->message ?? __('license-verifier::license-verifier.invalid_key')),
            'data' => $result->toArray(),
        ], $result->isUsable() ? 200 : 422);
    }

    public function deactivate(Request $request): JsonResponse
    {
        $this->authorizeManagement($request);

        return response()->json([
            'message' => __('license-verifier::license-verifier.deactivated_successfully'),
            'data' => ['deactivated' => $this->drivers->active()->deactivate()],
        ]);
    }

    /**
     * Enforce the configured Gate ability for license-management actions.
     */
    private function authorizeManagement(Request $request): void
    {
        $permission = config('license-verifier-vue.permission');

        if ($permission !== null && ! ($request->user()?->can($permission) ?? false)) {
            abort(403, 'You are not authorized to manage the license.');
        }
    }
}

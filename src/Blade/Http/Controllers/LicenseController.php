<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Blade\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Simtabi\Laranail\Licence\Verifier\Drivers\DriverManager;
use Simtabi\Laranail\Licence\Verifier\Presets\Blade\Http\Requests\ActivateLicenseRequest;
use Simtabi\Laranail\Licence\Verifier\Support\ReminderManager;
use Simtabi\Laranail\Licence\Verifier\ValueObjects\LicenseRequest;

/**
 * Driver-aware HTTP endpoints backing the Blade preset. All calls go through
 * the headless verifier (DriverManager), so the UI works for any provider.
 */
final class LicenseController extends Controller
{
    public function __construct(private readonly DriverManager $drivers) {}

    public function unlicensed(): View
    {
        return view('license-verifier-blade::unlicensed', [
            'fields' => $this->drivers->active()->activationFields(),
            'info' => $this->drivers->active()->getLicenseInfo(),
        ]);
    }

    public function status(): JsonResponse
    {
        $result = $this->drivers->active()->verify();

        return response()->json([
            'data' => $result->toArray(),
            'valid' => $result->isUsable(),
        ], $result->isUsable() ? 200 : 422);
    }

    public function activate(ActivateLicenseRequest $request): JsonResponse
    {
        $result = $this->drivers->active()->activate(new LicenseRequest(
            key: (string) $request->validated('license_key'),
            client: $request->validated('client'),
            metadata: ['agreement' => (bool) $request->boolean('agreement')],
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

        $deactivated = $this->drivers->active()->deactivate(reason: $request->input('reason'));

        return response()->json([
            'message' => __('license-verifier::license-verifier.deactivated_successfully'),
            'data' => ['deactivated' => $deactivated],
        ]);
    }

    public function skipReminder(Request $request, ReminderManager $reminder): JsonResponse
    {
        $this->authorizeManagement($request);

        $reminder->skip($request->integer('days') ?: null);

        return response()->json(['message' => 'Reminder skipped.']);
    }

    /**
     * Enforce the configured Gate ability for license-management actions.
     * No configured permission ⇒ rely on the route middleware group only.
     */
    private function authorizeManagement(Request $request): void
    {
        $permission = config('license-verifier-blade.permission');

        if ($permission !== null && ! ($request->user()?->can($permission) ?? false)) {
            abort(403, 'You are not authorized to manage the license.');
        }
    }
}

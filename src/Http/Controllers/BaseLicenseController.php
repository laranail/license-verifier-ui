<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Simtabi\Laranail\Licence\Verifier\Drivers\DriverManager;
use Simtabi\Laranail\Licence\Verifier\Presets\Http\Requests\ActivateLicenseRequest;
use Simtabi\Laranail\Licence\Verifier\Presets\Support\Concerns\AuthorizesLicenseActions;
use Simtabi\Laranail\Licence\Verifier\ValueObjects\LicenseRequest;

/**
 * Framework-agnostic JSON endpoints shared by every HTML preset (Blade, Vue).
 * All calls go through the headless verifier (DriverManager). Generated
 * controllers subclass this and supply configKey() (and, for Blade, view
 * rendering).
 */
abstract class BaseLicenseController extends Controller
{
    use AuthorizesLicenseActions;

    public function __construct(protected readonly DriverManager $drivers) {}

    /** The owning package's config key, e.g. "license-verifier-blade". */
    abstract protected function configKey(): string;

    public function status(): JsonResponse
    {
        $result = $this->drivers->active()->verify();

        return response()->json([
            'data' => $result->toArray(),
            'valid' => $result->isUsable(),
            'fields' => $this->drivers->active()->activationFields(),
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

        return response()->json([
            'message' => __('license-verifier::license-verifier.deactivated_successfully'),
            'data' => ['deactivated' => $this->drivers->active()->deactivate(reason: $request->input('reason'))],
        ]);
    }
}

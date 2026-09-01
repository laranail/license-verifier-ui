<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Support\Concerns;

use Illuminate\Http\Request;

/**
 * Shared license-management authorization. The consumer exposes its config key
 * via configKey(); a configured `<key>.permission` Gate ability is enforced,
 * otherwise access falls through to the route middleware.
 */
trait AuthorizesLicenseActions
{
    abstract protected function configKey(): string;

    protected function authorizeManagement(Request $request): void
    {
        $permission = config($this->configKey().'.permission');

        if ($permission !== null && ! ($request->user()?->can($permission) ?? false)) {
            abort(403, 'You are not authorized to manage the license.');
        }
    }
}

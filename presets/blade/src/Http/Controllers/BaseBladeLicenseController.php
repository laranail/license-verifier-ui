<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Blade\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Simtabi\Laranail\Licence\Verifier\Support\ReminderManager;
use Simtabi\Laranail\Licence\Verifier\Presets\Rendering\FieldRenderer;
use Simtabi\Laranail\Licence\Verifier\Presets\Http\Controllers\BaseLicenseController;

/**
 * Blade-specific base controller: the shared JSON endpoints plus the unlicensed
 * page and the reminder-skip action. The generated controller subclasses this
 * and supplies configKey()/viewNamespace().
 */
abstract class BaseBladeLicenseController extends BaseLicenseController
{
    public function unlicensed(FieldRenderer $fields): View
    {
        return view($this->viewNamespace() . '::unlicensed', [
            'fields' => $fields->normalize($this->drivers->active()->activationFields()),
            'info'   => $this->drivers->active()->getLicenseInfo(),
        ]);
    }

    public function skipReminder(Request $request, ReminderManager $reminder): JsonResponse
    {
        $this->authorizeManagement($request);

        $reminder->skip($request->integer('days') ?: null);

        return response()->json(['message' => 'Reminder skipped.']);
    }

    /** The owning package's view namespace, e.g. "license-verifier-blade". */
    abstract protected function viewNamespace(): string;
}

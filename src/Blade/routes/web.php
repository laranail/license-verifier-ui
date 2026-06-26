<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Simtabi\Laranail\Licence\Verifier\Presets\Blade\Http\Controllers\LicenseController;

Route::get('unlicensed', [LicenseController::class, 'unlicensed'])->name('unlicensed');
Route::get('status', [LicenseController::class, 'status'])->name('status');
Route::post('activate', [LicenseController::class, 'activate'])->name('activate');
Route::post('deactivate', [LicenseController::class, 'deactivate'])->name('deactivate');
Route::post('reminder/skip', [LicenseController::class, 'skipReminder'])->name('reminder.skip');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ServiceStatusController;

// Ajouter ces routes dans le groupe admin existant
Route::middleware(['auth', 'admin'])->group(function () {
    // Routes pour le monitoring des services IA
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/services/status', [ServiceStatusController::class, 'index'])
            ->name('services.status');
        Route::post('/services/reset-fallback', [ServiceStatusController::class, 'resetFallbackMode'])
            ->name('services.reset-fallback');
        Route::post('/services/reset-quota', [ServiceStatusController::class, 'resetQuotaCount'])
            ->name('services.reset-quota');
    });
});

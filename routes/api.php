<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\InverterApiController;

// Route de scan des onduleurs (accessible sans authentification)
Route::post('/onduleurs/scan', [InverterApiController::class, 'scan']);

Route::middleware('auth:sanctum')->group(function () {
    // Routes pour les données de performance
    Route::get('/regional-performance', 'App\Http\Controllers\Api\PerformanceController@getRegionalData');
    Route::get('/inverter-status', 'App\Http\Controllers\Api\PerformanceController@getInverterStatus');

    // Routes pour le système intelligent
    Route::get('/alertes', 'App\Http\Controllers\Api\SystemeIntelligentController@getAlertes');
    Route::get('/diagnostic', 'App\Http\Controllers\Api\SystemeIntelligentController@getDiagnostic');
    Route::get('/maintenances/next', 'App\Http\Controllers\Api\SystemeIntelligentController@getNextMaintenance');
    Route::get('/recommendations', 'App\Http\Controllers\Api\SystemeIntelligentController@getRecommendations');
    
    // Routes pour les rapports
    Route::get('/reports/download/{type}/{period}', 'App\Http\Controllers\Api\ReportController@download')
        ->where(['type' => 'pdf|excel', 'period' => 'journalier|hebdomadaire|mensuel']);
    Route::post('/reports/preferences', 'App\Http\Controllers\Api\ReportController@savePreferences');

    // Routes pour les onduleurs
    Route::prefix('inverters')->group(function () {
        // Routes existantes
        Route::get('/supported', 'App\Http\Controllers\InverterController@getSupportedInverters');
        Route::get('/{inverterName?}/status', 'App\Http\Controllers\InverterController@getStatus');
        Route::get('/{inverterName?}/alarms', 'App\Http\Controllers\InverterController@getAlarms');
        Route::get('/{inverterName?}/info', 'App\Http\Controllers\InverterController@getDeviceInfo');

        // Nouvelles routes
        Route::get('/{inverterName}/history/{period}', 'App\Http\Controllers\InverterController@getHistory')
            ->where('period', 'daily|weekly|monthly|yearly');
        Route::get('/{inverterName}/efficiency', 'App\Http\Controllers\InverterController@getEfficiency');
        Route::post('/{inverterName}/configure', 'App\Http\Controllers\InverterController@updateConfiguration');
        Route::post('/{inverterName}/firmware/update', 'App\Http\Controllers\InverterController@updateFirmware');
        Route::post('/{inverterName}/control', 'App\Http\Controllers\InverterController@controlProduction');
        Route::get('/{inverterName}/schedule', 'App\Http\Controllers\InverterController@getSchedule');
        Route::post('/{inverterName}/schedule', 'App\Http\Controllers\InverterController@updateSchedule');
        Route::get('/{inverterName}/diagnostics', 'App\Http\Controllers\InverterController@getDiagnostics');
        Route::post('/{inverterName}/reset', 'App\Http\Controllers\InverterController@resetDevice');
        Route::get('/{inverterName}/maintenance', 'App\Http\Controllers\InverterController@getMaintenanceInfo');
    });
});

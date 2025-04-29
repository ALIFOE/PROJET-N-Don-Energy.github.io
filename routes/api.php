<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Routes pour les données de performance
    Route::get('/regional-performance', 'App\Http\Controllers\Api\PerformanceController@getRegionalData');
    Route::get('/inverter-status', 'App\Http\Controllers\Api\PerformanceController@getInverterStatus');

    // Routes pour les rapports
    Route::get('/reports/download/{type}/{period}', 'App\Http\Controllers\Api\ReportController@download')
        ->where(['type' => 'pdf|excel', 'period' => 'journalier|hebdomadaire|mensuel']);
    Route::post('/reports/preferences', 'App\Http\Controllers\Api\ReportController@savePreferences');
});

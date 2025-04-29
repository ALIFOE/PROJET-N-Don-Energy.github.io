<?php

namespace App\Http\Controllers;

use App\Models\DonneeMeteo;
use App\Models\DonneeConsommation;
use Illuminate\Http\JsonResponse;

class RegionalPerformanceController extends Controller
{
    public function getPerformance(): JsonResponse
    {
        try {
            // Récupérer les dernières données météo
            $lastMeteoData = DonneeMeteo::latest()->first();
            
            // Récupérer les données de production régionale
            $regionalData = DonneeConsommation::where('created_at', '>=', now()->subHours(1))
                ->avg('production') ?? 0;

            // Calculer la performance collective
            $performance = $lastMeteoData ? ($regionalData / $lastMeteoData->irradiation) * 100 : 0;

            return response()->json([
                'production' => number_format($regionalData, 2),
                'irradiation' => number_format($lastMeteoData->irradiation ?? 0, 2),
                'performance' => number_format($performance, 1),
                'timestamp' => now()->toIso8601String()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la récupération des données régionales'
            ], 500);
        }
    }
}
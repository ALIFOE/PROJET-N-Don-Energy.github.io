<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DonneeProduction;
use App\Models\DonneeMeteo;
use Illuminate\Support\Facades\DB;

class RegionalPerformanceController extends Controller
{
    public function index()
    {
        // Récupérer la production totale de la région
        $production = DonneeProduction::select(DB::raw('SUM(puissance_instantanee) as total_production'))
            ->whereDate('date_heure', now()->toDateString())
            ->first();

        // Récupérer l'irradiation solaire moyenne
        $irradiation = DonneeMeteo::select('irradiance')
            ->whereDate('date_mesure', now()->toDateString())
            ->orderBy('date_mesure', 'desc')
            ->first();

        // Calculer la performance collective (rendement moyen)
        $performance = DonneeProduction::select(DB::raw('AVG(rendement) as rendement_moyen'))
            ->whereDate('date_heure', now()->toDateString())
            ->first();

        return response()->json([
            'production' => number_format($production->total_production / 1000, 2), // Conversion en kW
            'irradiation' => $irradiation ? round($irradiation->irradiance) : 0,
            'performance' => $performance ? round($performance->rendement_moyen, 1) : 0
        ]);
    }
}
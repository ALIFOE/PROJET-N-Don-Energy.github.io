<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Installation;
use App\Models\ProductionData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RealtimeProductionController extends Controller
{
    public function getData()
    {
        // Récupère toutes les installations pour l'administrateur
        $installations = Installation::with(['productionData' => function($query) {
            $query->orderBy('timestamp', 'desc')->take(24);
        }])->get();

        $labels = ['00:00', '02:00', '04:00', '06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00'];
        
        // Initialise les tableaux de données
        $production = array_fill(0, 12, 0);
        $consommation = array_fill(0, 12, 0);

        foreach ($installations as $installation) {
            foreach ($installation->productionData as $data) {
                $hour = (int)$data->timestamp->format('H');
                $index = floor($hour / 2);
                
                if (isset($production[$index])) {
                    $production[$index] += $data->current_power;
                    $consommation[$index] += $data->daily_energy;
                }
            }
        }

        return response()->json([
            'labels' => $labels,
            'production' => $production,
            'consommation' => $consommation
        ]);
    }
}

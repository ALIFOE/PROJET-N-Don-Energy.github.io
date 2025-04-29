<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Installation;
use App\Models\ProductionData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RealtimeDataController extends Controller
{
    public function getRealtimeData(Request $request)
    {
        $user = Auth::user();
        $installation = Installation::where('utilisateur_id', $user->id)
            ->with(['productionData' => function($query) {
                $query->orderBy('timestamp', 'desc')->take(24);
            }])
            ->first();

        if (!$installation) {
            return response()->json([
                'error' => 'Aucune installation trouvée'
            ], 404);
        }

        $latestData = $installation->productionData->first();
        $historicalData = $installation->productionData->sortBy('timestamp');

        return response()->json([
            'current' => [
                'power' => $latestData->current_power ?? 0,
                'daily_energy' => $latestData->daily_energy ?? 0,
                'total_energy' => $latestData->total_energy ?? 0,
                'temperature' => $latestData->temperature ?? 0,
                'irradiance' => $latestData->irradiance ?? 0,
                'efficiency' => $latestData->efficiency ?? 0,
                'timestamp' => $latestData->timestamp ?? now(),
            ],
            'historical' => $historicalData->map(function($data) {
                return [
                    'timestamp' => $data->timestamp->format('H:i'),
                    'power' => $data->current_power,
                    'energy' => $data->daily_energy,
                ];
            })->values(),
        ]);
    }
} 
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Onduleur;
use Illuminate\Http\JsonResponse;

class PerformanceController extends Controller
{
    public function getRegionalData(): JsonResponse
    {
        try {
            // Récupération des données de performance régionale
            $data = [
                'production' => number_format(rand(50, 150), 1), // Exemple de données simulées
                'irradiation' => number_format(rand(600, 1000), 0),
                'performance' => number_format(rand(85, 95), 1)
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getInverterStatus(): JsonResponse
    {
        try {
            // Récupérer l'onduleur de l'utilisateur connecté
            $onduleur = Onduleur::where('user_id', auth()->id())->first();

            if (!$onduleur) {
                return response()->json([
                    'currentProduction' => '0.0',
                    'dailyProduction' => '0.0',
                    'status' => 'Non connecté'
                ]);
            }

            // Simuler des données de production
            $data = [
                'currentProduction' => number_format(rand(1, 10), 1),
                'dailyProduction' => number_format(rand(5, 50), 1),
                'status' => $onduleur->est_connecte ? 'Connecté' : 'Déconnecté'
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
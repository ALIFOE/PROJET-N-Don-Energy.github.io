<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;

class DimensionnementIAController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function calculer(Request $request)
    {
        try {
            $request->validate([
                'consommation' => 'required|numeric',
                'surface_dispo' => 'required|numeric',
                'type_toiture' => 'required|string',
                'orientation' => 'required|string',
                'objectifs' => 'required|string'
            ]);

            $prompt = $this->prepareDimensionnementPrompt($request->all());
            $result = $this->openAIService->generateQuote($prompt);
            
            return response()->json([
                'success' => true,
                'dimensionnement' => $result
            ]);
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'insufficient_quota')) {
                return response()->json([
                    'success' => false,
                    'message' => "Le service d'analyse IA est temporairement indisponible pour cause de quota dépassé. Veuillez réessayer plus tard."
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => "Une erreur est survenue lors du calcul du dimensionnement: " . $e->getMessage()
            ], 500);
        }
    }

    protected function prepareDimensionnementPrompt(array $params): string
    {
        return "En tant qu'expert en énergie solaire, calcule le dimensionnement optimal pour une installation avec les caractéristiques suivantes:\n\n" .
               "Consommation annuelle: {$params['consommation']} kWh\n" .
               "Surface disponible: {$params['surface_dispo']} m²\n" .
               "Type de toiture: {$params['type_toiture']}\n" .
               "Orientation: {$params['orientation']}\n" .
               "Objectifs spécifiques: {$params['objectifs']}\n\n" .
               "Fournis un dimensionnement détaillé incluant:\n" .
               "1. Nombre et puissance des panneaux solaires\n" .
               "2. Type et capacité du système de stockage\n" .
               "3. Caractéristiques de l'onduleur recommandé\n" .
               "4. Production estimée annuelle\n" .
               "5. Taux d'autoconsommation estimé\n" .
               "6. Recommandations techniques spécifiques";
    }
}

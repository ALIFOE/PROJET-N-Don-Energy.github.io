<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;

class AnalyseProductionIAController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function analyser(Request $request)
    {
        try {
            $request->validate([
                'periode' => 'required|string',
                'type_installation' => 'required|string',
                'puissance' => 'required|numeric',
                'stockage' => 'required|string',
                'donnees' => 'required|string'
            ]);

            $donnees = array_filter(explode("\n", $request->donnees));
            if (empty($donnees)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune donnée valide fournie pour l\'analyse'
                ], 400);
            }

            $prompt = $this->prepareAnalysisPrompt($request->all(), $donnees);
            $analyse = $this->openAIService->generateQuote($prompt);

            return response()->json([
                'success' => true,
                'analyse' => $analyse
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
                'message' => "Une erreur est survenue lors de l'analyse: " . $e->getMessage()
            ], 500);
        }
    }

    protected function prepareAnalysisPrompt(array $params, array $donnees): string
    {
        return "En tant qu'expert en énergie solaire, analyse les données de production suivantes pour une installation:\n\n" .
               "Type d'installation: {$params['type_installation']}\n" .
               "Puissance installée: {$params['puissance']} kWc\n" .
               "Période d'analyse: {$params['periode']}\n" .
               "Système de stockage: {$params['stockage']}\n\n" .
               "Données de production (kWh):\n" . implode("\n", $donnees) . "\n\n" .
               "Fournis une analyse détaillée incluant:\n" .
               "1. La performance globale de l'installation\n" .
               "2. Les tendances et variations notables\n" .
               "3. Des recommandations d'optimisation\n" .
               "4. La comparaison avec les performances attendues\n" .
               "5. Des suggestions d'amélioration spécifiques";
    }
}

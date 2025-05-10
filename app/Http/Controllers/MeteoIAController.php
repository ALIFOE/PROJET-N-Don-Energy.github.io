<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Log;

class MeteoIAController extends Controller
{
    protected $openAIService;
    protected $weatherService;

    public function __construct(OpenAIService $openAIService, WeatherService $weatherService)
    {
        $this->openAIService = $openAIService;
        $this->weatherService = $weatherService;
    }

    public function prevoir(Request $request)
    {
        try {
            $request->validate([
                'ville' => 'required|string',
            ]);            
            $ville = $request->input('ville');
            Log::info("Demande de prévision météo pour: {$ville}");

            // Récupération des données météo
            $meteoData = $this->weatherService->getForecast($ville);
            Log::info("Données météo récupérées pour {$ville}");

            try {
                // Préparation et analyse IA
                $prompt = $this->prepareAnalysisPrompt($ville, $meteoData);
                $analysis = $this->openAIService->generateQuote($prompt);
                
                return response()->json([
                    'success' => true,
                    'donnees_meteo' => $meteoData,
                    'analyse' => $analysis
                ]);
            } catch (\Exception $e) {
                // Vérifier si c'est une erreur de quota dépassé
                if (str_contains($e->getMessage(), 'insufficient_quota')) {
                    return response()->json([
                        'success' => true,
                        'donnees_meteo' => $meteoData,
                        'message' => "Le service d'analyse IA est temporairement indisponible pour cause de quota dépassé. Veuillez réessayer plus tard. En attendant, vous pouvez consulter les données météorologiques brutes ci-dessous."
                    ]);
                }
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error("Erreur lors de l'analyse météo: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Une erreur est survenue: " . $e->getMessage()
            ], 500);
        }
    }    protected function prepareAnalysisPrompt(string $ville, array $meteoData): string
    {
        return "En tant qu'expert en énergie solaire, analyse les prévisions météo suivantes pour {$ville} sur les prochaines 24 heures. " .
               "Fournis une analyse détaillée incluant:\n\n" .
               "1. Les conditions météorologiques générales et leurs variations\n" .
               "2. L'impact probable sur la production d'énergie solaire\n" .
               "3. Des recommandations pour optimiser la production dans ces conditions\n" .
               "4. Les périodes optimales pour la production d'énergie\n\n" .
               "Données météo: " . json_encode($meteoData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

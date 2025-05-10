<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;
use Illuminate\Support\Facades\Log;

class DevisIAController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function generate(Request $request)
    {
        try {
            $validated = $request->validate([
                'type_propriete' => 'required|string',
                'surface' => 'required|numeric',
                'budget' => 'required|numeric',
                'region' => 'required|string',
                'description' => 'required|string'
            ]);

            // Vérifier l'état du service avant de continuer
            $quotaStatus = $this->openAIService->getQuotaStatus();
            if ($quotaStatus['usage_percentage'] > 95) {
                Log::warning('DevisIAController: Quota critique', $quotaStatus);
                return response()->json([
                    'success' => false,
                    'message' => 'Le service est actuellement très sollicité. Veuillez réessayer plus tard.',
                    'status' => $quotaStatus['status']
                ], 503);
            }

            Log::info('DevisIAController: Début de la génération du devis', [
                'params' => $validated,
                'quota_status' => $quotaStatus['status']
            ]);

            $prompt = $this->prepareDevisPrompt($validated);
            $result = $this->openAIService->generateQuote($prompt);
            
            return response()->json([
                'success' => true,
                'devis' => $result,
                'service_status' => $quotaStatus['status']
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('DevisIAController: Erreur de validation', [
                'errors' => $e->errors()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('DevisIAController: Erreur lors de la génération', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $statusCode = $this->getStatusCode($e);
            $message = $this->getErrorMessage($e);

            return response()->json([
                'success' => false,
                'message' => $message,
                'service_status' => $this->openAIService->getQuotaStatus()['status']
            ], $statusCode);
        }
    }

    protected function prepareDevisPrompt(array $params): string
    {
        return "En tant qu'expert en énergie solaire, génère un devis détaillé pour le projet suivant:\n\n" .
               "Type de propriété: {$params['type_propriete']}\n" .
               "Surface habitable: {$params['surface']} m²\n" .
               "Budget approximatif: {$params['budget']} €\n" .
               "Région: {$params['region']}\n" .
               "Description du projet: {$params['description']}\n\n" .
               "Format de réponse souhaité:\n" .
               "1. Analyse préliminaire\n" .
               "2. Solution recommandée\n" .
               "3. Estimation des coûts détaillée\n" .
               "4. Économies potentielles\n" .
               "5. Délai estimé\n" .
               "6. Recommandations supplémentaires";
    }

    protected function getErrorMessage(\Exception $e): string
    {
        $quotaStatus = $this->openAIService->getQuotaStatus();
        
        if ($quotaStatus['fallback_mode']) {
            return "Notre service est temporairement en maintenance. Un expert analysera votre demande dans les plus brefs délais.";
        }
        
        if (str_contains($e->getMessage(), 'insufficient_quota') || $quotaStatus['usage_percentage'] > 90) {
            return "Le service est actuellement très sollicité. Votre demande sera traitée dès que possible.";
        }
        
        if (str_contains($e->getMessage(), 'rate_limit')) {
            return "Le service est momentanément saturé. Veuillez réessayer dans quelques minutes.";
        }

        return "Une erreur technique est survenue lors de l'analyse. Notre équipe a été notifiée et travaille sur la résolution.";
    }

    protected function getStatusCode(\Exception $e): int
    {
        if (str_contains($e->getMessage(), 'insufficient_quota') || 
            str_contains($e->getMessage(), 'rate_limit')) {
            return 503; // Service Unavailable
        }
        
        return 500; // Internal Server Error
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.openai.com/v1';
    protected $maxRetries = 3;
    protected $retryDelayMs = 1000; // 1 seconde
    protected $quotaMonitoring;

    public function __construct(QuotaMonitoringService $quotaMonitoring)
    {
        $this->apiKey = config('services.openai.key');
        $this->quotaMonitoring = $quotaMonitoring;
    }

    public function generateQuote($prompt)
    {
        Log::info('OpenAIService: Début de la génération');
        
        if (!$this->apiKey) {
            Log::error('OpenAIService: Clé API manquante');
            throw new \Exception('Clé API OpenAI non configurée');
        }

        // Vérifier si on est en mode dégradé
        if ($this->quotaMonitoring->isInFallbackMode()) {
            Log::warning('OpenAIService: Service en mode dégradé');
            return $this->getFallbackResponse($prompt);
        }

        $attempt = 1;
        while ($attempt <= $this->maxRetries) {
            try {
                Log::info('OpenAIService: Tentative ' . $attempt, [
                    'prompt' => $prompt
                ]);
                
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])->post($this->baseUrl . '/chat/completions', [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'Tu es un assistant expert en énergie solaire.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => 1000,
                    'temperature' => 0.7,
                ]);

                if ($response->successful()) {
                    $result = $response->json();
                    
                    if (isset($result['choices'][0]['message']['content'])) {
                        $this->quotaMonitoring->recordUsage();
                        $this->quotaMonitoring->resetErrorCount();
                        Log::info('OpenAIService: Réponse générée avec succès');
                        return $result['choices'][0]['message']['content'];
                    }

                    throw new \Exception('Format de réponse OpenAI invalide');
                }

                $statusCode = $response->status();
                $body = $response->json();
                
                if ($statusCode === 429 || str_contains(($body['error']['message'] ?? ''), 'insufficient_quota')) {
                    $this->quotaMonitoring->recordError();
                    throw new \Exception('Quota OpenAI dépassé', 429);
                }

                throw new \Exception('Erreur OpenAI: ' . ($body['error']['message'] ?? 'Erreur inconnue'));

            } catch (\Exception $e) {
                Log::error('OpenAIService: Erreur', [
                    'attempt' => $attempt,
                    'message' => $e->getMessage()
                ]);

                if ($attempt === $this->maxRetries || !$this->shouldRetry($e)) {
                    throw $e;
                }
                
                usleep($this->retryDelayMs * 1000 * $attempt);
                $attempt++;
            }
        }
    }

    protected function shouldRetry(\Exception $e): bool
    {
        $message = $e->getMessage();
        return str_contains($message, 'insufficient_quota') ||
               str_contains($message, 'rate_limit_exceeded') ||
               str_contains($message, 'timeout');
    }

    protected function getFallbackResponse($prompt): string
    {
        Log::info('OpenAIService: Utilisation de la réponse de secours');
        return "Nous vous prions de nous excuser, mais notre service d'analyse est temporairement en maintenance. ".
               "Un expert vous contactera dans les plus brefs délais pour traiter votre demande. ".
               "Vous pouvez également réessayer dans quelques minutes.";
    }

    public function getQuotaStatus(): array
    {
        return $this->quotaMonitoring->getQuotaStatus();
    }
}
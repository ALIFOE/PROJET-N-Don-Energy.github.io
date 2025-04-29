<?php

namespace App\Services\Inverters;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SMAConnector implements InverterConnectorInterface
{
    private string $ipAddress;
    private string $password;
    private ?string $sessionId = null;

    public function __construct(string $ipAddress, string $password)
    {
        $this->ipAddress = $ipAddress;
        $this->password = $password;
    }

    private function login(): bool
    {
        try {
            $response = Http::post("https://{$this->ipAddress}/dyn/login.json", [
                'right' => 'usr',
                'pass' => $this->password
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $this->sessionId = $data['sid'] ?? null;
                return $this->sessionId !== null;
            }
            return false;
        } catch (\Exception $e) {
            \Log::error("Erreur de connexion à l'onduleur SMA: " . $e->getMessage());
            return false;
        }
    }

    public function fetchRealTimeData(): array
    {
        $cacheKey = 'sma_inverter_realtime_data';
        
        return Cache::remember($cacheKey, 60, function () {
            try {
                if (!$this->sessionId && !$this->login()) {
                    throw new \Exception("Échec de l'authentification SMA");
                }

                $response = Http::withHeaders([
                    'Cookie' => "JSESSIONID={$this->sessionId}"
                ])->get("https://{$this->ipAddress}/dyn/getValues.json");
                
                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'current_power' => $data['6100_40263F00']['1'][0]['val'] ?? 0,
                        'daily_energy' => $this->getDailyProduction(),
                        'status' => $this->getStatus(),
                        'timestamp' => now()->timestamp
                    ];
                }
                
                throw new \Exception("Échec de la récupération des données SMA: " . $response->status());
            } catch (\Exception $e) {
                \Log::error("Erreur de connexion à l'onduleur SMA: " . $e->getMessage());
                return [
                    'current_power' => 0,
                    'daily_energy' => 0,
                    'status' => 3,
                    'timestamp' => now()->timestamp
                ];
            }
        });
    }

    public function getDailyProduction(): float
    {
        try {
            if (!$this->sessionId && !$this->login()) {
                return 0.0;
            }

            $response = Http::withHeaders([
                'Cookie' => "JSESSIONID={$this->sessionId}"
            ])->get("https://{$this->ipAddress}/dyn/getDailyYield.json");

            if ($response->successful()) {
                return (float) ($response->json()['6400_00262200']['1'][0]['val'] ?? 0.0);
            }
            return 0.0;
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la récupération de la production journalière SMA: " . $e->getMessage());
            return 0.0;
        }
    }

    public function getStatus(): int
    {
        try {
            if (!$this->sessionId && !$this->login()) {
                return 3;
            }

            $response = Http::withHeaders([
                'Cookie' => "JSESSIONID={$this->sessionId}"
            ])->get("https://{$this->ipAddress}/dyn/getEvents.json");

            if ($response->successful()) {
                $events = $response->json()['events'] ?? [];
                
                // Vérifie les événements récents pour déterminer le statut
                foreach ($events as $event) {
                    $level = $event['level'] ?? 'info';
                    if ($level === 'error') {
                        return 2;
                    } elseif ($level === 'warning') {
                        return 1;
                    }
                }
                return 0; // Si aucun événement critique n'est trouvé
            }
            return 3;
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la récupération du statut SMA: " . $e->getMessage());
            return 3;
        }
    }

    public function __destruct()
    {
        // Déconnexion de la session si nécessaire
        if ($this->sessionId) {
            try {
                Http::post("https://{$this->ipAddress}/dyn/logout.json", [
                    'sid' => $this->sessionId
                ]);
            } catch (\Exception $e) {
                \Log::error("Erreur lors de la déconnexion SMA: " . $e->getMessage());
            }
        }
    }
}
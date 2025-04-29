<?php

namespace App\Services\Inverters;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SchneiderConnector implements InverterConnectorInterface
{
    private string $ipAddress;
    private string $username;
    private string $password;
    private ?string $token = null;

    public function __construct(string $ipAddress, string $username, string $password)
    {
        $this->ipAddress = $ipAddress;
        $this->username = $username;
        $this->password = $password;
    }

    private function authenticate(): bool
    {
        try {
            $response = Http::post("http://{$this->ipAddress}/api/login", [
                'username' => $this->username,
                'password' => $this->password
            ]);

            if ($response->successful()) {
                $this->token = $response->json('token');
                return true;
            }
            return false;
        } catch (\Exception $e) {
            \Log::error("Erreur d'authentification Schneider: " . $e->getMessage());
            return false;
        }
    }

    public function fetchRealTimeData(): array
    {
        $cacheKey = 'schneider_inverter_realtime_data';
        
        return Cache::remember($cacheKey, 60, function () {
            try {
                if (!$this->token && !$this->authenticate()) {
                    throw new \Exception("Échec de l'authentification Schneider");
                }

                $response = Http::withToken($this->token)
                    ->get("http://{$this->ipAddress}/api/realtime/metrics");

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'current_power' => $data['activePower'] ?? 0,
                        'daily_energy' => $this->getDailyProduction(),
                        'status' => $this->getStatus(),
                        'timestamp' => now()->timestamp
                    ];
                }

                throw new \Exception("Échec de la récupération des données Schneider");
            } catch (\Exception $e) {
                \Log::error("Erreur de connexion à l'onduleur Schneider: " . $e->getMessage());
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
            if (!$this->token && !$this->authenticate()) {
                return 0.0;
            }

            $response = Http::withToken($this->token)
                ->get("http://{$this->ipAddress}/api/energy/daily");

            if ($response->successful()) {
                return (float) ($response->json()['dailyEnergy'] ?? 0.0);
            }
            return 0.0;
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la récupération de la production journalière Schneider: " . $e->getMessage());
            return 0.0;
        }
    }

    public function getStatus(): int
    {
        try {
            if (!$this->token && !$this->authenticate()) {
                return 3;
            }

            $response = Http::withToken($this->token)
                ->get("http://{$this->ipAddress}/api/status");

            if ($response->successful()) {
                $status = $response->json()['deviceStatus'] ?? 'offline';
                return match ($status) {
                    'running' => 0,
                    'warning' => 1,
                    'fault' => 2,
                    default => 3
                };
            }
            return 3;
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la récupération du statut Schneider: " . $e->getMessage());
            return 3;
        }
    }
}
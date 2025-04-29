<?php

namespace App\Services\Inverters;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class HuaweiConnector implements InverterConnectorInterface
{
    private string $apiUrl;
    private string $apiKey;

    public function __construct(string $apiUrl, string $apiKey)
    {
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
    }

    public function fetchRealTimeData(): array
    {
        $cacheKey = 'huawei_inverter_realtime_data';
        
        return Cache::remember($cacheKey, 60, function () {
            try {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Content-Type' => 'application/json'
                ])->get("{$this->apiUrl}/device/realtime-data");
                
                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'current_power' => $data['activePower'] ?? 0,
                        'daily_energy' => $this->getDailyProduction(),
                        'status' => $this->getStatus(),
                        'timestamp' => now()->timestamp
                    ];
                }
                
                throw new \Exception("Échec de la récupération des données Huawei: " . $response->status());
            } catch (\Exception $e) {
                \Log::error("Erreur de connexion à l'onduleur Huawei: " . $e->getMessage());
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
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}"
            ])->get("{$this->apiUrl}/device/daily-yield");

            if ($response->successful()) {
                return (float) ($response->json()['dailyYield'] ?? 0.0);
            }
            return 0.0;
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la récupération de la production journalière Huawei: " . $e->getMessage());
            return 0.0;
        }
    }

    public function getStatus(): int
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}"
            ])->get("{$this->apiUrl}/device/status");

            if ($response->successful()) {
                $status = $response->json()['deviceStatus'] ?? 'disconnected';
                return match ($status) {
                    'running' => 0,
                    'warning' => 1,
                    'fault' => 2,
                    default => 3
                };
            }
            return 3;
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la récupération du statut Huawei: " . $e->getMessage());
            return 3;
        }
    }
}
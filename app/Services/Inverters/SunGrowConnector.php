<?php

namespace App\Services\Inverters;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SunGrowConnector implements InverterConnectorInterface
{
    private string $host;
    private int $port;

    public function __construct(string $host, int $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function fetchRealTimeData(): array
    {
        $cacheKey = 'inverter_realtime_data';
        
        return Cache::remember($cacheKey, 60, function () {
            try {
                $response = Http::timeout(5)->get("http://{$this->host}:{$this->port}/api/realtime");
                
                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'current_power' => $data['activePower'] ?? 0,
                        'daily_energy' => $this->getDailyProduction(),
                        'status' => $this->getStatus(),
                        'timestamp' => now()->timestamp
                    ];
                }
                
                throw new \Exception("Échec de la récupération des données: " . $response->status());
            } catch (\Exception $e) {
                \Log::error("Erreur de connexion à l'onduleur: " . $e->getMessage());
                return [
                    'current_power' => 0,
                    'daily_energy' => 0,
                    'status' => 3, // Hors ligne
                    'timestamp' => now()->timestamp
                ];
            }
        });
    }

    public function getDailyProduction(): float
    {
        try {
            $response = Http::get("http://{$this->host}:{$this->port}/api/daily-energy");
            return $response->successful() ? ($response->json()['energy'] ?? 0.0) : 0.0;
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la récupération de la production journalière: " . $e->getMessage());
            return 0.0;
        }
    }

    public function getStatus(): int
    {
        try {
            $response = Http::get("http://{$this->host}:{$this->port}/api/status");
            if ($response->successful()) {
                return match ($response->json()['status'] ?? 'offline') {
                    'normal' => 0,
                    'warning' => 1,
                    'error' => 2,
                    default => 3
                };
            }
            return 3;
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la récupération du statut: " . $e->getMessage());
            return 3;
        }
    }
}
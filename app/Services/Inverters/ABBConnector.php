<?php

namespace App\Services\Inverters;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ABBConnector implements InverterConnectorInterface
{
    private string $ipAddress;
    private int $port;
    private string $serialNumber;

    public function __construct(string $ipAddress, int $port, string $serialNumber)
    {
        $this->ipAddress = $ipAddress;
        $this->port = $port;
        $this->serialNumber = $serialNumber;
    }

    public function fetchRealTimeData(): array
    {
        $cacheKey = 'abb_inverter_realtime_data';
        
        return Cache::remember($cacheKey, 60, function () {
            try {
                $response = Http::timeout(5)->get("http://{$this->ipAddress}:{$this->port}/v1/devices/{$this->serialNumber}/realtime");
                
                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'current_power' => $data['measurements']['power']['value'] ?? 0,
                        'daily_energy' => $this->getDailyProduction(),
                        'status' => $this->getStatus(),
                        'timestamp' => now()->timestamp,
                        'voltage_dc' => $data['measurements']['voltage_dc']['value'] ?? 0,
                        'current_dc' => $data['measurements']['current_dc']['value'] ?? 0,
                        'frequency' => $data['measurements']['frequency']['value'] ?? 0,
                        'temperature' => $data['measurements']['temperature']['value'] ?? 0
                    ];
                }
                
                throw new \Exception("Échec de la récupération des données ABB");
            } catch (\Exception $e) {
                \Log::error("Erreur de connexion à l'onduleur ABB: " . $e->getMessage());
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
            $response = Http::get("http://{$this->ipAddress}:{$this->port}/v1/devices/{$this->serialNumber}/energy/daily");

            if ($response->successful()) {
                $data = $response->json();
                return (float) ($data['energy']['today'] ?? 0.0);
            }
            return 0.0;
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la récupération de la production journalière ABB: " . $e->getMessage());
            return 0.0;
        }
    }

    public function getStatus(): int
    {
        try {
            $response = Http::get("http://{$this->ipAddress}:{$this->port}/v1/devices/{$this->serialNumber}/status");

            if ($response->successful()) {
                $data = $response->json();
                $status = $data['state'] ?? 'unknown';
                
                return match ($status) {
                    'operating' => 0,
                    'warning' => 1,
                    'error', 'fault' => 2,
                    default => 3
                };
            }
            return 3;
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la récupération du statut ABB: " . $e->getMessage());
            return 3;
        }
    }

    public function getDetailedStatus(): array
    {
        try {
            $response = Http::get("http://{$this->ipAddress}:{$this->port}/v1/devices/{$this->serialNumber}/alarms");

            if ($response->successful()) {
                return $response->json()['alarms'] ?? [];
            }
            return [];
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la récupération des alarmes ABB: " . $e->getMessage());
            return [];
        }
    }
}
<?php

namespace App\Services\Inverters;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class FroniusConnector implements InverterConnectorInterface
{
    private string $baseUrl;
    private string $deviceId;

    public function __construct(string $ipAddress, string $deviceId)
    {
        $this->baseUrl = "http://{$ipAddress}/solar_api/v1";
        $this->deviceId = $deviceId;
    }

    public function fetchRealTimeData(): array
    {
        $cacheKey = 'fronius_inverter_realtime_data';
        
        return Cache::remember($cacheKey, 60, function () {
            try {
                $response = Http::timeout(5)->get("{$this->baseUrl}/GetInverterRealtimeData.cgi", [
                    'Scope' => 'Device',
                    'DeviceId' => $this->deviceId,
                    'DataCollection' => 'CommonInverterData'
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    $body = $data['Body']['Data'] ?? [];
                    
                    return [
                        'current_power' => $body['PAC']['Value'] ?? 0,
                        'daily_energy' => $this->getDailyProduction(),
                        'status' => $this->getStatus(),
                        'timestamp' => now()->timestamp
                    ];
                }
                
                throw new \Exception("Échec de la récupération des données Fronius: " . $response->status());
            } catch (\Exception $e) {
                \Log::error("Erreur de connexion à l'onduleur Fronius: " . $e->getMessage());
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
            $response = Http::get("{$this->baseUrl}/GetArchiveData.cgi", [
                'Scope' => 'Device',
                'DeviceId' => $this->deviceId,
                'StartDate' => now()->startOfDay()->format('Y-m-d H:i:s'),
                'EndDate' => now()->format('Y-m-d H:i:s'),
                'Channel' => 'EnergyReal_WAC_Sum_Produced'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return (float) ($data['Body']['Data']['0']['Values']['0'] ?? 0.0);
            }
            return 0.0;
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la récupération de la production journalière Fronius: " . $e->getMessage());
            return 0.0;
        }
    }

    public function getStatus(): int
    {
        try {
            $response = Http::get("{$this->baseUrl}/GetInverterInfo.cgi", [
                'DeviceId' => $this->deviceId
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $status = $data['Body']['Data'][$this->deviceId]['StatusCode'] ?? 0;
                
                return match ($status) {
                    0, 1, 2, 3, 4, 5, 6, 7 => 0, // États normaux de fonctionnement
                    8, 9, 10, 11 => 1, // États d'avertissement
                    default => 2 // États d'erreur
                };
            }
            return 3;
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la récupération du statut Fronius: " . $e->getMessage());
            return 3;
        }
    }
}
<?php

namespace App\Services\Inverters;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class DeltaConnector implements InverterConnectorInterface
{
    private string $ipAddress;
    private string $username;
    private string $password;
    private ?string $sessionId = null;

    public function __construct(string $ipAddress, string $username, string $password)
    {
        $this->ipAddress = $ipAddress;
        $this->username = $username;
        $this->password = $password;
    }

    private function login(): bool
    {
        try {
            $response = Http::post("http://{$this->ipAddress}/auth", [
                'username' => $this->username,
                'password' => $this->password
            ]);

            if ($response->successful()) {
                $this->sessionId = $response->json('sessionId');
                return true;
            }
            return false;
        } catch (\Exception $e) {
            \Log::error("Erreur de connexion Delta: " . $e->getMessage());
            return false;
        }
    }

    public function fetchRealTimeData(): array
    {
        $cacheKey = 'delta_inverter_realtime_data';
        
        return Cache::remember($cacheKey, 60, function () {
            try {
                if (!$this->sessionId && !$this->login()) {
                    throw new \Exception("Échec de l'authentification Delta");
                }

                $response = Http::withHeaders([
                    'X-Session-ID' => $this->sessionId
                ])->get("http://{$this->ipAddress}/api/v1/realtime-data");

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'current_power' => $data['instantPower'] ?? 0,
                        'daily_energy' => $this->getDailyProduction(),
                        'status' => $this->getStatus(),
                        'timestamp' => now()->timestamp,
                        'dc_voltage' => $data['dcVoltage'] ?? 0,
                        'ac_voltage' => $data['acVoltage'] ?? 0,
                        'efficiency' => $data['efficiency'] ?? 0
                    ];
                }

                throw new \Exception("Échec de la récupération des données Delta");
            } catch (\Exception $e) {
                \Log::error("Erreur de connexion à l'onduleur Delta: " . $e->getMessage());
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
                'X-Session-ID' => $this->sessionId
            ])->get("http://{$this->ipAddress}/api/v1/daily-yield");

            if ($response->successful()) {
                return (float) ($response->json()['dailyYield'] ?? 0.0);
            }
            return 0.0;
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la récupération de la production journalière Delta: " . $e->getMessage());
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
                'X-Session-ID' => $this->sessionId
            ])->get("http://{$this->ipAddress}/api/v1/status");

            if ($response->successful()) {
                $status = $response->json()['operationStatus'] ?? 'offline';
                return match ($status) {
                    'normal' => 0,
                    'warning' => 1,
                    'fault' => 2,
                    default => 3
                };
            }
            return 3;
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la récupération du statut Delta: " . $e->getMessage());
            return 3;
        }
    }

    public function __destruct()
    {
        if ($this->sessionId) {
            try {
                Http::withHeaders([
                    'X-Session-ID' => $this->sessionId
                ])->post("http://{$this->ipAddress}/auth/logout");
            } catch (\Exception $e) {
                \Log::error("Erreur lors de la déconnexion Delta: " . $e->getMessage());
            }
        }
    }
}
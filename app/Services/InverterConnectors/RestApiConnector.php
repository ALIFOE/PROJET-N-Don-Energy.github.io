<?php

namespace App\Services\InverterConnectors;

use Exception;
use Illuminate\Support\Facades\Http;

class RestApiConnector extends BaseInverterConnector
{
    protected $baseUrl;
    protected $apiKey;
    protected $headers = [];

    public function connect(): bool
    {
        try {
            $this->baseUrl = $this->config['base_url'] ?? "http://{$this->inverter->ip_address}";
            $this->apiKey = $this->config['api_key'] ?? null;
            
            // Configuration des headers d'authentification selon le fabricant
            switch ($this->inverter->brand) {
                case 'solaredge':
                    $this->headers['Authorization'] = "Bearer {$this->apiKey}";
                    break;
                
                case 'fronius':
                    if ($this->inverter->username && $this->inverter->password) {
                        $this->headers['Authorization'] = 'Basic ' . base64_encode(
                            $this->inverter->username . ':' . $this->inverter->password
                        );
                    }
                    break;
                    
                default:
                    // Configuration par défaut
                    if ($this->apiKey) {
                        $this->headers['X-API-Key'] = $this->apiKey;
                    }
            }

            // Test de connexion
            $response = Http::withHeaders($this->headers)
                ->timeout(5)
                ->get("{$this->baseUrl}/status");

            $this->connected = $response->successful();
            
            if ($this->connected) {
                $this->logger->info("Connecté à l'API REST de l'onduleur: {$this->baseUrl}");
            } else {
                throw new Exception("Échec de connexion à l'API: " . $response->body());
            }
            
            return $this->connected;
        } catch (Exception $e) {
            $this->logger->error("Erreur de connexion à l'API REST: " . $e->getMessage());
            $this->connected = false;
            return false;
        }
    }

    public function disconnect(): bool
    {
        $this->connected = false;
        return true;
    }

    public function getRealtimeData(): array
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        try {
            $endpoint = $this->getEndpoint('realtime');
            $response = Http::withHeaders($this->headers)
                ->timeout(5)
                ->get("{$this->baseUrl}{$endpoint}");

            if (!$response->successful()) {
                throw new Exception("Erreur API: " . $response->body());
            }

            $data = $this->parseRealtimeData($response->json());
            $this->saveReading($data);
            
            return $data;
        } catch (Exception $e) {
            $this->logger->error("Erreur de lecture des données temps réel: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    public function getHistoricalData(string $startDate, string $endDate): array
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        try {
            $endpoint = $this->getEndpoint('historical');
            $response = Http::withHeaders($this->headers)
                ->timeout(10)
                ->get("{$this->baseUrl}{$endpoint}", [
                    'startTime' => $startDate,
                    'endTime' => $endDate
                ]);

            if (!$response->successful()) {
                throw new Exception("Erreur API: " . $response->body());
            }

            return $this->parseHistoricalData($response->json());
        } catch (Exception $e) {
            $this->logger->error("Erreur de lecture des données historiques: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    public function getDeviceInfo(): array
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        try {
            $endpoint = $this->getEndpoint('info');
            $response = Http::withHeaders($this->headers)
                ->timeout(5)
                ->get("{$this->baseUrl}{$endpoint}");

            if (!$response->successful()) {
                throw new Exception("Erreur API: " . $response->body());
            }

            return $this->parseDeviceInfo($response->json());
        } catch (Exception $e) {
            $this->logger->error("Erreur de lecture des informations: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    protected function getEndpoint(string $type): string
    {
        $endpoints = [
            'solaredge' => [
                'realtime' => '/api/v1/livedata',
                'historical' => '/api/v1/data',
                'info' => '/api/v1/details'
            ],
            'fronius' => [
                'realtime' => '/solar_api/v1/GetInverterRealtimeData.cgi',
                'historical' => '/solar_api/v1/GetArchiveData.cgi',
                'info' => '/solar_api/v1/GetInverterInfo.cgi'
            ],
            'default' => [
                'realtime' => '/api/realtime',
                'historical' => '/api/historical',
                'info' => '/api/info'
            ]
        ];

        $brandEndpoints = $endpoints[$this->inverter->brand] ?? $endpoints['default'];
        return $brandEndpoints[$type];
    }

    protected function parseRealtimeData(array $response): array
    {
        // Adaptation selon le fabricant
        switch ($this->inverter->brand) {
            case 'solaredge':
                return [
                    'power' => $response['power']['value'] ?? 0,
                    'daily_energy' => $response['energy']['today'] ?? 0,
                    'total_energy' => $response['energy']['total'] ?? 0,
                    'voltage_ac' => $response['voltage']['ac'] ?? 0,
                    'current_ac' => $response['current']['ac'] ?? 0,
                    'frequency' => $response['frequency'] ?? 0,
                    'temperature' => $response['temperature'] ?? 0,
                    'status' => $response['status'] ?? 'unknown'
                ];

            case 'fronius':
                $data = $response['Body']['Data'] ?? [];
                return [
                    'power' => $data['PAC']['Value'] ?? 0,
                    'daily_energy' => $data['DAY_ENERGY']['Value'] ?? 0,
                    'total_energy' => $data['TOTAL_ENERGY']['Value'] ?? 0,
                    'voltage_ac' => $data['UAC']['Value'] ?? 0,
                    'current_ac' => $data['IAC']['Value'] ?? 0,
                    'frequency' => $data['FAC']['Value'] ?? 0,
                    'temperature' => $data['TEMPERATURE']['Value'] ?? 0,
                    'status' => $data['STATUS']['Value'] ?? 'unknown'
                ];

            default:
                return $response;
        }
    }

    protected function parseHistoricalData(array $response): array
    {
        // Adaptation selon le fabricant
        switch ($this->inverter->brand) {
            case 'solaredge':
                return array_map(function($entry) {
                    return [
                        'timestamp' => $entry['date'],
                        'data' => [
                            'power' => $entry['power'] ?? 0,
                            'energy' => $entry['energy'] ?? 0,
                            'voltage' => $entry['voltage'] ?? 0,
                            'current' => $entry['current'] ?? 0
                        ]
                    ];
                }, $response['data'] ?? []);

            case 'fronius':
                return array_map(function($entry) {
                    return [
                        'timestamp' => $entry['timestamp'],
                        'data' => [
                            'power' => $entry['values']['PAC'] ?? 0,
                            'energy' => $entry['values']['DAY_ENERGY'] ?? 0,
                            'voltage' => $entry['values']['UAC'] ?? 0,
                            'current' => $entry['values']['IAC'] ?? 0
                        ]
                    ];
                }, $response['Body']['Data']['Data'] ?? []);

            default:
                return $response;
        }
    }

    protected function parseDeviceInfo(array $response): array
    {
        // Adaptation selon le fabricant
        switch ($this->inverter->brand) {
            case 'solaredge':
                return [
                    'manufacturer' => 'SolarEdge',
                    'model' => $response['model'] ?? 'Unknown',
                    'serial' => $response['serialNumber'] ?? '',
                    'firmware' => $response['firmwareVersion'] ?? ''
                ];

            case 'fronius':
                $info = $response['Body']['Data'] ?? [];
                return [
                    'manufacturer' => 'Fronius',
                    'model' => $info['DeviceType'] ?? 'Unknown',
                    'serial' => $info['UniqueID'] ?? '',
                    'firmware' => $info['FWVersion'] ?? ''
                ];

            default:
                return $response;
        }
    }
}
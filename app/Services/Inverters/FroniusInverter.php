<?php

namespace App\Services\Inverters;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Carbon\Carbon;

class FroniusInverter extends AdvancedInverter
{
    private $client;

    // ...existing connection and basic methods...

    protected function applyConfiguration(array $settings): array
    {
        try {
            $response = $this->client->post("/solar_api/v1/SetInverterConfig.cgi", [
                'query' => [
                    'DeviceId' => $this->config['device_id']
                ],
                'json' => $settings
            ]);
            
            $data = json_decode($response->getBody(), true);
            return [
                'success' => $data['Head']['Status']['Code'] === 0,
                'message' => $data['Head']['Status']['Reason'],
                'applied_settings' => $settings
            ];
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    protected function performSoftReset(): bool
    {
        try {
            $response = $this->client->post("/solar_api/v1/InverterReboot.cgi", [
                'query' => [
                    'DeviceId' => $this->config['device_id'],
                    'RebootMode' => 'soft'
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return $data['Head']['Status']['Code'] === 0;
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return false;
        }
    }

    protected function performHardReset(): bool
    {
        try {
            $response = $this->client->post("/solar_api/v1/InverterReboot.cgi", [
                'query' => [
                    'DeviceId' => $this->config['device_id'],
                    'RebootMode' => 'hard'
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return $data['Head']['Status']['Code'] === 0;
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return false;
        }
    }

    protected function performFactoryReset(): bool
    {
        try {
            $response = $this->client->post("/solar_api/v1/InverterReboot.cgi", [
                'query' => [
                    'DeviceId' => $this->config['device_id'],
                    'RebootMode' => 'factory'
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return $data['Head']['Status']['Code'] === 0;
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return false;
        }
    }

    public function updateFirmware(string $firmwarePath, string $version): array
    {
        try {
            $response = $this->client->post("/solar_api/v1/UpdateFirmware.cgi", [
                'query' => [
                    'DeviceId' => $this->config['device_id']
                ],
                'multipart' => [
                    [
                        'name' => 'firmware',
                        'contents' => fopen($firmwarePath, 'r'),
                        'filename' => basename($firmwarePath)
                    ],
                    [
                        'name' => 'version',
                        'contents' => $version
                    ]
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return [
                'success' => $data['Head']['Status']['Code'] === 0,
                'message' => $data['Head']['Status']['Reason'],
                'update_id' => $data['Body']['Data']['UpdateId'] ?? null
            ];
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function controlProduction(string $action, ?float $powerLimit = null): bool
    {
        try {
            $endpoint = match($action) {
                'start' => 'InverterOn',
                'stop' => 'InverterOff',
                'restart' => 'InverterRestart',
                default => throw new \InvalidArgumentException("Action non supportée: $action")
            };

            $params = [
                'DeviceId' => $this->config['device_id']
            ];

            if ($powerLimit !== null) {
                $params['PowerLimit'] = $powerLimit;
            }

            $response = $this->client->post("/solar_api/v1/$endpoint.cgi", [
                'query' => $params
            ]);
            
            $data = json_decode($response->getBody(), true);
            return $data['Head']['Status']['Code'] === 0;
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return false;
        }
    }

    public function runDiagnostics(): array
    {
        try {
            $response = $this->client->get("/solar_api/v1/GetDiagnosticData.cgi", [
                'query' => [
                    'DeviceId' => $this->config['device_id'],
                    'Scope' => 'System'
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            return [
                'system_health' => $this->analyzeSystemHealth($data),
                'performance_metrics' => $this->analyzePerformance($data),
                'connection_quality' => $this->analyzeConnectionQuality($data),
                'recommendations' => $this->generateRecommendations($data)
            ];
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return [];
        }
    }

    public function getMaintenanceHistory(): array
    {
        try {
            $response = $this->client->get("/solar_api/v1/GetServiceLog.cgi", [
                'query' => [
                    'DeviceId' => $this->config['device_id']
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            return array_map(function ($entry) {
                return [
                    'date' => Carbon::parse($entry['Timestamp'])->toDateTime(),
                    'type' => $entry['ServiceType'],
                    'description' => $entry['Description'],
                    'technician' => $entry['Technician'] ?? 'N/A',
                    'parts_replaced' => $entry['PartsReplaced'] ?? []
                ];
            }, $data['Body']['Data']['ServiceLog'] ?? []);
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return [];
        }
    }

    protected function getIdentifier(): string
    {
        return 'fronius_' . $this->config['device_id'] . '@' . $this->config['ip_address'];
    }

    private function analyzeSystemHealth(array $data): array
    {
        $healthData = $data['Body']['Data']['Health'] ?? [];
        return [
            'overall_status' => $healthData['Status'] ?? 'unknown',
            'temperature_status' => $this->evaluateTemperature($healthData['Temperature'] ?? null),
            'component_status' => $this->evaluateComponents($healthData['Components'] ?? []),
            'last_error' => $healthData['LastError'] ?? null
        ];
    }

    private function analyzePerformance(array $data): array
    {
        $perfData = $data['Body']['Data']['Performance'] ?? [];
        return [
            'efficiency' => $perfData['Efficiency'] ?? 0,
            'power_factor' => $perfData['PowerFactor'] ?? 0,
            'mppt_efficiency' => $perfData['MPPTEfficiency'] ?? 0,
            'daily_yield_deviation' => $perfData['YieldDeviation'] ?? 0
        ];
    }

    private function analyzeConnectionQuality(array $data): array
    {
        $connData = $data['Body']['Data']['Connection'] ?? [];
        return [
            'signal_strength' => $connData['SignalStrength'] ?? 0,
            'connection_stability' => $connData['Stability'] ?? 'unknown',
            'last_communication' => $connData['LastCommunication'] ?? null
        ];
    }

    private function generateRecommendations(array $data): array
    {
        $recommendations = [];
        $healthData = $data['Body']['Data']['Health'] ?? [];

        if (($healthData['Temperature'] ?? 0) > 70) {
            $recommendations[] = [
                'priority' => 'high',
                'action' => 'Vérifier le système de refroidissement',
                'reason' => 'Température élevée détectée'
            ];
        }

        // Autres recommandations basées sur les données...
        return $recommendations;
    }

    private function evaluateTemperature(?float $temp): string
    {
        if ($temp === null) return 'unknown';
        return match(true) {
            $temp > 80 => 'critical',
            $temp > 70 => 'warning',
            $temp > 60 => 'attention',
            default => 'normal'
        };
    }

    private function evaluateComponents(array $components): array
    {
        $status = [];
        foreach ($components as $component => $data) {
            $status[$component] = [
                'status' => $data['Status'] ?? 'unknown',
                'health' => $data['Health'] ?? 0,
                'last_checked' => $data['LastCheck'] ?? null
            ];
        }
        return $status;
    }

    public function connect(): bool
    {
        try {
            $this->client = new Client([
                'base_uri' => 'http://' . $this->config['ip_address'],
                'verify' => false
            ]); 
            
            // Test de connexion
            $response = $this->client->get("/solar_api/v1/GetInverterInfo.cgi");
            $data = json_decode($response->getBody(), true);
            
            $this->connected = $response->getStatusCode() === 200 && 
                             ($data['Head']['Status']['Code'] === 0);
            
            return $this->connected;
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return false;
        }
    }

    public function disconnect(): void
    {
        $this->client = null;
        $this->connected = false;
    }

    public function getCurrentEfficiency(): float
    {
        try {
            $response = $this->client->get("/solar_api/v1/GetInverterRealtimeData.cgi", [
                'query' => [
                    'Scope' => 'Device',
                    'DeviceId' => $this->config['device_id'],
                    'DataCollection' => 'CommonInverterData'
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return (float) ($data['Body']['Data']['ETA']['Value'] ?? 0.0);
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getCurrentPower(): float
    {
        try {
            $response = $this->client->get("/solar_api/v1/GetInverterRealtimeData.cgi", [
                'query' => [
                    'Scope' => 'Device',
                    'DeviceId' => $this->config['device_id'],
                    'DataCollection' => 'CommonInverterData'
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return (float) ($data['Body']['Data']['PAC']['Value'] ?? 0.0);
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getDailyEnergy(): float
    {
        try {
            $response = $this->client->get("/solar_api/v1/GetInverterRealtimeData.cgi", [
                'query' => [
                    'Scope' => 'Device',
                    'DeviceId' => $this->config['device_id'],
                    'DataCollection' => 'CommonInverterData'
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return (float) ($data['Body']['Data']['DAY_ENERGY']['Value'] ?? 0.0);
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getTotalEnergy(): float
    {
        try {
            $response = $this->client->get("/solar_api/v1/GetInverterRealtimeData.cgi", [
                'query' => [
                    'Scope' => 'Device',
                    'DeviceId' => $this->config['device_id'],
                    'DataCollection' => 'CommonInverterData'
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return (float) ($data['Body']['Data']['TOTAL_ENERGY']['Value'] ?? 0.0);
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getStatus(): array
    {
        try {
            $response = $this->client->get("/solar_api/v1/GetInverterRealtimeData.cgi", [
                'query' => [
                    'Scope' => 'Device',
                    'DeviceId' => $this->config['device_id'],
                    'DataCollection' => 'CommonInverterData'
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            $bodyData = $data['Body']['Data'] ?? [];
            
            return [
                'state' => $bodyData['DeviceStatus']['StatusCode'] ?? 'unknown',
                'power_ac' => (float) ($bodyData['PAC']['Value'] ?? 0.0),
                'voltage_ac' => (float) ($bodyData['UAC']['Value'] ?? 0.0),
                'current_ac' => (float) ($bodyData['IAC']['Value'] ?? 0.0),
                'frequency' => (float) ($bodyData['FAC']['Value'] ?? 0.0),
                'voltage_dc' => (float) ($bodyData['UDC']['Value'] ?? 0.0),
                'current_dc' => (float) ($bodyData['IDC']['Value'] ?? 0.0),
                'temperature' => (float) ($bodyData['TEMPERATURE']['Value'] ?? 0.0),
                'daily_energy' => (float) ($bodyData['DAY_ENERGY']['Value'] ?? 0.0),
                'total_energy' => (float) ($bodyData['TOTAL_ENERGY']['Value'] ?? 0.0),
                'year_energy' => (float) ($bodyData['YEAR_ENERGY']['Value'] ?? 0.0)
            ];
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return [];
        }
    }

    public function getDeviceInfo(): array
    {
        try {
            $response = $this->client->get("/solar_api/v1/GetInverterInfo.cgi", [
                'query' => [
                    'DeviceId' => $this->config['device_id']
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            $deviceData = $data['Body']['Data'][$this->config['device_id']] ?? [];
            
            return [
                'manufacturer' => 'Fronius',
                'model' => $deviceData['ModelName'] ?? 'Unknown',
                'serial' => $deviceData['Serial'] ?? '',
                'firmware' => $deviceData['SW_Version'] ?? '',
                'rated_power' => $deviceData['Capabilities']['MaxPowerAC'] ?? 0,
                'phases' => $deviceData['Capabilities']['NumberPhases'] ?? 0
            ];
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return [];
        }
    }

    public function getAlarms(): array
    {
        try {
            $response = $this->client->get("/solar_api/v1/GetActiveAlerts.cgi", [
                'query' => [
                    'DeviceId' => $this->config['device_id']
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            $alerts = [];
            
            foreach ($data['Body']['Data']['ActiveAlerts'] ?? [] as $alert) {
                $alerts[] = [
                    'code' => $alert['Code'],
                    'message' => $alert['Message'],
                    'timestamp' => Carbon::parse($alert['Timestamp'])->toDateTime(),
                    'severity' => $this->mapAlertSeverity($alert['Severity'])
                ];
            }
            
            return $alerts;
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return [];
        }
    }

    private function mapAlertSeverity(int $severity): string
    {
        return match($severity) {
            0 => 'info',
            1 => 'warning',
            2 => 'error',
            3 => 'critical',
            default => 'unknown'
        };
    }

    protected function validateConfig(): bool
    {
        return isset($this->config['ip_address']) && 
               isset($this->config['device_id']);
    }
}

<?php

namespace App\Services\Inverters;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class DeltaInverter extends BaseInverter
{
    private $client;
    private $token;

    private function handleGuzzleError(GuzzleException $e): void
    {
        $this->handleError(new \Exception($e->getMessage(), $e->getCode(), $e));
    }

    public function connect(): bool
    {
        try {
            $this->client = new Client([
                'base_uri' => 'http://' . $this->config['ip_address'],
                'verify' => false
            ]);

            // Authentification Delta
            $response = $this->client->post('/api/login', [
                'json' => [
                    'username' => $this->config['username'],
                    'password' => $this->config['password']
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            $this->token = $data['token'];
            $this->connected = true;
            
            return true;
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e);
            return false;
        }
    }

    public function disconnect(): void
    {
        if ($this->token) {
            try {
                $this->client->post('/api/logout', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->token
                    ]
                ]);
            } catch (GuzzleException $e) {
                // Ignorer les erreurs lors de la déconnexion
            }
        }
        $this->client = null;
        $this->token = null;
        $this->connected = false;
    }

    public function getCurrentPower(): float
    {
        try {
            $response = $this->client->get('/api/realtime/power', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return (float) ($data['activePower'] ?? 0.0);
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e);
            return 0.0;
        }
    }

    public function getDailyEnergy(): float
    {
        try {
            $response = $this->client->get('/api/energy/daily', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return (float) ($data['dailyEnergy'] ?? 0.0);
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e);
            return 0.0;
        }
    }

    public function getTotalEnergy(): float
    {
        try {
            $response = $this->client->get('/api/energy/total', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return (float) ($data['totalEnergy'] ?? 0.0);
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e);
            return 0.0;
        }
    }

    public function getStatus(): array
    {
        try {
            $response = $this->client->get('/api/status', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            return [
                'state' => $this->mapOperatingState($data['operatingState'] ?? 'unknown'),
                'power' => $data['activePower'] ?? 0.0,
                'voltage_dc' => $data['dcVoltage'] ?? 0.0,
                'current_dc' => $data['dcCurrent'] ?? 0.0,
                'voltage_ac' => $data['acVoltage'] ?? 0.0,
                'current_ac' => $data['acCurrent'] ?? 0.0,
                'frequency' => $data['gridFrequency'] ?? 0.0,
                'temperature' => $data['temperature'] ?? 0.0,
                'power_factor' => $data['powerFactor'] ?? 0.0
            ];
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e);
            return [];
        }
    }

    public function getAlarms(): array
    {
        try {
            $response = $this->client->get('/api/alarms', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            return array_map(function ($alarm) {
                return [
                    'code' => $alarm['code'],
                    'message' => $alarm['description'],
                    'timestamp' => $alarm['timestamp'],
                    'severity' => $this->mapAlarmSeverity($alarm['level'])
                ];
            }, $data['alarms'] ?? []);
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e);
            return [];
        }
    }

    public function getDeviceInfo(): array
    {
        try {
            $response = $this->client->get('/api/device/info', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            return [
                'manufacturer' => 'Delta',
                'model' => $data['model'] ?? '',
                'serial' => $data['serialNumber'] ?? '',
                'firmware' => $data['firmwareVersion'] ?? '',
                'hardware' => $data['hardwareVersion'] ?? '',
                'rated_power' => $data['ratedPower'] ?? ''
            ];
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e);
            return [];
        }
    }

    protected function validateConfig(): bool
    {
        return isset($this->config['ip_address']) && 
               isset($this->config['username']) && 
               isset($this->config['password']);
    }

    private function mapOperatingState(string $state): string
    {
        $states = [
            'OFF' => 'off',
            'STANDBY' => 'standby',
            'STARTUP' => 'starting',
            'RUNNING' => 'running',
            'SHUTDOWN' => 'shutting_down',
            'FAULT' => 'fault',
            'SLEEPING' => 'sleeping',
            'EMERGENCY' => 'emergency'
        ];
        
        return $states[strtoupper($state)] ?? 'unknown';
    }

    private function mapAlarmSeverity(string $level): string
    {
        $severityMap = [
            'CRITICAL' => 'critical',
            'WARNING' => 'warning',
            'INFO' => 'info'
        ];
        
        return $severityMap[strtoupper($level)] ?? 'unknown';
    }

    public function controlProduction(string $action, ?float $powerLimit = null): bool
    {
        try {
            $response = $this->client->post('/api/control/production', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'json' => [
                    'action' => $action,
                    'powerLimit' => $powerLimit
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return $data['success'] ?? false;
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e);
            return false;
        }
    }

    public function getCurrentEfficiency(): float
    {
        try {
            $status = $this->getStatus();
            $voltage_dc = $status['voltage_dc'] ?? 0;
            $current_dc = $status['current_dc'] ?? 0;
            $voltage_ac = $status['voltage_ac'] ?? 0;
            $current_ac = $status['current_ac'] ?? 0;
            
            // Vérification de toutes les valeurs
            if (!is_numeric($voltage_dc) || !is_numeric($current_dc) || 
                !is_numeric($voltage_ac) || !is_numeric($current_ac)) {
                return 0.0;
            }
            
            $dcPower = $voltage_dc * $current_dc;
            $acPower = $voltage_ac * $current_ac;
            
            // Vérification que dcPower est suffisamment grand pour éviter la division par des valeurs très proches de zéro
            if ($dcPower > 0.001) {
                $efficiency = ($acPower / $dcPower) * 100;
                // Vérification que le résultat est dans une plage raisonnable
                return ($efficiency >= 0 && $efficiency <= 100) ? $efficiency : 0.0;
            }
            
            return 0.0;
        } catch (\Exception $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getDailyAverageEfficiency(): float
    {
        try {
            $response = $this->client->get('/api/efficiency/daily', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return (float) ($data['averageEfficiency'] ?? 0.0);
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e);
            return 0.0;
        }
    }

    public function getMonthlyAverageEfficiency(): float
    {
        try {
            $response = $this->client->get('/api/efficiency/monthly', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return (float) ($data['averageEfficiency'] ?? 0.0);
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e);
            return 0.0;
        }
    }

    public function getEfficiencyFactors(): array
    {
        try {
            $status = $this->getStatus();
            return [
                'temperature' => $status['temperature'],
                'power_factor' => $status['power_factor'],
                'voltage_dc' => $status['voltage_dc'],
                'current_dc' => $status['current_dc']
            ];
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e);
            return [];
        }
    }

    public function getLastMaintenanceDate(): ?\DateTime
    {
        try {
            $response = $this->client->get('/api/maintenance/last', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return $data['lastMaintenanceDate'] ? new \DateTime($data['lastMaintenanceDate']) : null;
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e);
            return null;
        }
    }

    public function getNextMaintenanceDate(): ?\DateTime
    {
        try {
            $response = $this->client->get('/api/maintenance/next', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return $data['nextMaintenanceDate'] ? new \DateTime($data['nextMaintenanceDate']) : null;
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e);
            return null;
        }
    }

    public function getMaintenanceHistory(): array
    {
        try {
            $response = $this->client->get('/api/maintenance/history', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return $data['history'] ?? [];
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e);
            return [];
        }
    }

    public function getRecommendedMaintenanceActions(): array
    {
        try {
            $response = $this->client->get('/api/maintenance/recommendations', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return $data['recommendations'] ?? [];
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e);
            return [];
        }
    }

    public function reset(string $type): bool
    {
        try {
            $response = $this->client->post('/api/reset', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'json' => [
                    'type' => $type
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return $data['success'] ?? false;
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e);
            return false;
        }
    }

    public function runDiagnostics(): array
    {
        try {
            $response = $this->client->get('/api/diagnostics', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return $data['results'] ?? [];
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e);
            return [];
        }
    }

    public function updateConfiguration(array $settings): array
    {
        try {
            $response = $this->client->post('/api/config/update', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'json' => $settings
            ]);
            
            return json_decode($response->getBody(), true) ?? [];
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e);
            return [
                'success' => false,
                'message' => 'Configuration update failed'
            ];
        }
    }

    public function updateFirmware(string $firmwarePath, string $version): array
    {
        try {
            $response = $this->client->post('/api/firmware/update', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
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
            
            return json_decode($response->getBody(), true) ?? [];
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e);
            return [
                'success' => false,
                'message' => 'Firmware update failed'
            ];
        }
    }
}

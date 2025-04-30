<?php

namespace App\Services\Inverters;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Carbon\Carbon;

class HuaweiInverter extends AdvancedInverter
{
    private $client;
    private $apiKey;

    // ...existing connection methods...

    protected function applyConfiguration(array $settings): array
    {
        try {
            $response = $this->client->post('/api/configuration', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json'
                ],
                'json' => $settings
            ]);
            
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return [];
        }
    }

    protected function performSoftReset(): bool
    {
        try {
            $response = $this->client->post('/api/reset', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey
                ],
                'json' => [
                    'type' => 'soft'
                ]
            ]);
            
            return $response->getStatusCode() === 200;
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return false;
        }
    }

    protected function performHardReset(): bool
    {
        try {
            $response = $this->client->post('/api/reset', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey
                ],
                'json' => [
                    'type' => 'hard'
                ]
            ]);
            
            return $response->getStatusCode() === 200;
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return false;
        }
    }

    protected function performFactoryReset(): bool
    {
        try {
            $response = $this->client->post('/api/reset', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey
                ],
                'json' => [
                    'type' => 'factory'
                ]
            ]);
            
            return $response->getStatusCode() === 200;
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return false;
        }
    }

    public function updateFirmware(string $firmwarePath, string $version): array
    {
        try {
            $response = $this->client->post('/api/firmware/update', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey
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
            
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function controlProduction(string $action, ?float $powerLimit = null): bool
    {
        try {
            $data = ['action' => $action];
            if ($powerLimit !== null) {
                $data['power_limit'] = $powerLimit;
            }

            $response = $this->client->post('/api/control', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey
                ],
                'json' => $data
            ]);
            
            return $response->getStatusCode() === 200;
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return false;
        }
    }

    public function runDiagnostics(): array
    {
        try {
            $response = $this->client->get('/api/diagnostics', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey
                ]
            ]);
            
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return [];
        }
    }

    public function getMaintenanceHistory(): array
    {
        try {
            $response = $this->client->get('/api/maintenance/history', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey
                ]
            ]);
            
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return [];
        }
    }

    protected function getIdentifier(): string
    {
        return 'huawei_' . str_replace(['http://', 'https://', '/'], '', $this->config['api_url']);
    }

    protected function validateConfig(): bool
    {
        return isset($this->config['api_url']) &&
               isset($this->config['api_key']);
    }

    public function connect(): bool
    {
        try {
            if (!$this->validateConfig()) {
                throw new \Exception('Invalid configuration');
            }

            $this->client = new Client([
                'base_uri' => $this->config['api_url'],
                'verify' => false
            ]);

            $this->apiKey = $this->config['api_key'];

            // Test la connexion
            $response = $this->client->get('/api/status', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey
                ]
            ]);

            $this->connected = $response->getStatusCode() === 200;
            return $this->connected;
        } catch (\Exception $e) {
            $this->handleError($e);
            return false;
        }
    }

    public function disconnect(): void
    {
        $this->client = null;
        $this->apiKey = null;
        $this->connected = false;
    }

    public function getCurrentPower(): float
    {
        try {
            $response = $this->client->get('/api/realtime-data/power', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return (float) ($data['power'] ?? 0.0);
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getDailyEnergy(): float
    {
        try {
            $response = $this->client->get('/api/energy/daily', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return (float) ($data['energy'] ?? 0.0);
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getTotalEnergy(): float
    {
        try {
            $response = $this->client->get('/api/energy/total', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return (float) ($data['energy'] ?? 0.0);
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getStatus(): array
    {
        try {
            $response = $this->client->get('/api/status', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return [
                'state' => $data['state'] ?? 'unknown',
                'power' => (float) ($data['power'] ?? 0.0),
                'voltage_dc' => (float) ($data['voltage_dc'] ?? 0.0),
                'current_dc' => (float) ($data['current_dc'] ?? 0.0),
                'voltage_ac' => (float) ($data['voltage_ac'] ?? 0.0),
                'current_ac' => (float) ($data['current_ac'] ?? 0.0),
                'frequency' => (float) ($data['frequency'] ?? 0.0),
                'temperature' => (float) ($data['temperature'] ?? 0.0),
                'efficiency' => (float) ($data['efficiency'] ?? 0.0)
            ];
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return [];
        }
    }

    public function getDeviceInfo(): array
    {
        try {
            $response = $this->client->get('/api/device-info', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return [
                'manufacturer' => 'Huawei',
                'model' => $data['model'] ?? 'Unknown',
                'serial' => $data['serial'] ?? '',
                'firmware' => $data['firmware_version'] ?? '',
                'rated_power' => $data['rated_power'] ?? 0
            ];
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return [];
        }
    }

    public function getAlarms(): array
    {
        try {
            $response = $this->client->get('/api/alarms', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            $alarms = [];
            
            foreach ($data['alarms'] ?? [] as $alarm) {
                $alarms[] = [
                    'code' => $alarm['code'],
                    'message' => $alarm['message'],
                    'timestamp' => Carbon::parse($alarm['timestamp'])->toDateTime(),
                    'severity' => $alarm['severity']
                ];
            }
            
            return $alarms;
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return [];
        }
    }
}

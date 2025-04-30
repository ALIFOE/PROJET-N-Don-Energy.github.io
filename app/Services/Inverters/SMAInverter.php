<?php

namespace App\Services\Inverters;
 
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Carbon\Carbon;

class SMAInverter extends AdvancedInverter
{
    private $client;
    private $sessionId;

    public function connect(): bool
    {
        try {
            $this->client = new Client([
                'base_uri' => 'http://' . $this->config['ip_address'],
                'verify' => false
            ]);

            // Authentification SMA
            $response = $this->client->post('/dyn/login.json', [
                'json' => [
                    'right' => 'usr',
                    'pass' => $this->config['password']
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            $this->sessionId = $data['result']['sid'];
            $this->connected = true;
            
            return true;
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return false;
        }
    }

    public function disconnect(): void
    {
        if ($this->sessionId) {
            try {
                $this->client->post('/dyn/logout.json', [
                    'json' => ['sid' => $this->sessionId]
                ]);
            } catch (GuzzleException $e) {
                // Ignorer les erreurs lors de la déconnexion
            }
        }
        $this->client = null;
        $this->sessionId = null;
        $this->connected = false;
    }

    protected function applyConfiguration(array $settings): array
    {
        try {
            $response = $this->client->post('/dyn/setConfig.json', [
                'json' => [
                    'sid' => $this->sessionId,
                    'params' => $settings
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return [
                'success' => $data['result']['status'] === 0,
                'message' => $data['result']['msg'] ?? 'Configuration appliquée',
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
        return $this->performReset('soft');
    }

    protected function performHardReset(): bool
    {
        return $this->performReset('hard');
    }

    protected function performFactoryReset(): bool
    {
        return $this->performReset('factory');
    }

    private function performReset(string $type): bool
    {
        try {
            $response = $this->client->post('/dyn/device/reset.json', [
                'json' => [
                    'sid' => $this->sessionId,
                    'resetType' => $type
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return $data['result']['status'] === 0;
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return false;
        }
    }

    public function updateFirmware(string $firmwarePath, string $version): array
    {
        try {
            $response = $this->client->post('/dyn/device/firmware.json', [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($firmwarePath, 'r'),
                        'filename' => basename($firmwarePath)
                    ],
                    [
                        'name' => 'sid',
                        'contents' => $this->sessionId
                    ]
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return [
                'success' => $data['result']['status'] === 0,
                'message' => $data['result']['msg'] ?? 'Mise à jour initiée',
                'update_id' => $data['result']['updateId'] ?? null
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
            $params = [
                'sid' => $this->sessionId,
                'action' => $action
            ];

            if ($powerLimit !== null) {
                $params['powerLimit'] = $powerLimit;
            }

            $response = $this->client->post('/dyn/device/control.json', [
                'json' => $params
            ]);
            
            $data = json_decode($response->getBody(), true);
            return $data['result']['status'] === 0;
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return false;
        }
    }

    public function getCurrentEfficiency(): float
    {
        try {
            $response = $this->client->post('/dyn/getValues.json', [
                'json' => [
                    'sid' => $this->sessionId,
                    'keys' => ['6400_00543100'] // Clé pour l'efficacité
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return (float) ($data['result']['6400_00543100']['1'][0]['val'] ?? 0.0);
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function runDiagnostics(): array
    {
        try {
            $response = $this->client->post('/dyn/device/diagnostic.json', [
                'json' => [
                    'sid' => $this->sessionId
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return [
                'system_status' => $this->analyzeSystemStatus($data),
                'grid_quality' => $this->analyzeGridQuality($data),
                'performance_ratio' => $this->calculatePerformanceRatio($data),
                'communication_quality' => $this->analyzeCommunicationQuality($data)
            ];
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return [];
        }
    }

    public function getMaintenanceHistory(): array
    {
        try {
            $response = $this->client->post('/dyn/device/maintenance.json', [
                'json' => [
                    'sid' => $this->sessionId
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            return array_map(function ($entry) {
                return [
                    'date' => Carbon::createFromTimestamp($entry['timestamp'])->toDateTime(),
                    'type' => $entry['type'],
                    'description' => $entry['description'],
                    'technician' => $entry['technician'] ?? 'N/A',
                    'components' => $entry['components'] ?? []
                ];
            }, $data['result']['entries'] ?? []);
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return [];
        }
    }

    public function getCurrentPower(): float
    {
        try {
            $response = $this->client->post('/dyn/getValues.json', [
                'json' => [
                    'sid' => $this->sessionId,
                    'keys' => ['6100_40233100'] // Clé pour la puissance active totale
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return (float) ($data['result']['6100_40233100']['1'][0]['val'] ?? 0.0);
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getDailyEnergy(): float
    {
        try {
            $response = $this->client->post('/dyn/getValues.json', [
                'json' => [
                    'sid' => $this->sessionId,
                    'keys' => ['6400_00262200'] // Clé pour l'énergie journalière
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return (float) ($data['result']['6400_00262200']['1'][0]['val'] ?? 0.0);
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getTotalEnergy(): float
    {
        try {
            $response = $this->client->post('/dyn/getValues.json', [
                'json' => [
                    'sid' => $this->sessionId,
                    'keys' => ['6400_00260100'] // Clé pour l'énergie totale
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return (float) ($data['result']['6400_00260100']['1'][0]['val'] ?? 0.0);
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getStatus(): array
    {
        try {
            $response = $this->client->post('/dyn/getValues.json', [
                'json' => [
                    'sid' => $this->sessionId,
                    'keys' => [
                        '6100_00214800', // État de fonctionnement
                        '6100_40233100', // Puissance active
                        '6100_00464800', // Tension DC
                        '6100_40465300', // Courant DC
                        '6100_00464600', // Tension AC
                        '6100_40465700', // Courant AC
                        '6100_00465700', // Fréquence réseau
                        '6100_00237700'  // Température
                    ]
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            return [
                'state' => $this->getOperatingState($data['result']['6100_00214800']['1'][0]['val'] ?? 0),
                'power_ac' => (float) ($data['result']['6100_40233100']['1'][0]['val'] ?? 0.0),
                'voltage_dc' => (float) ($data['result']['6100_00464800']['1'][0]['val'] ?? 0.0),
                'current_dc' => (float) ($data['result']['6100_40465300']['1'][0]['val'] ?? 0.0),
                'voltage_ac' => (float) ($data['result']['6100_00464600']['1'][0]['val'] ?? 0.0),
                'current_ac' => (float) ($data['result']['6100_40465700']['1'][0]['val'] ?? 0.0),
                'frequency' => (float) ($data['result']['6100_00465700']['1'][0]['val'] ?? 0.0),
                'temperature' => (float) ($data['result']['6100_00237700']['1'][0]['val'] ?? 0.0)
            ];
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return [];
        }
    }

    private function getOperatingState(int $state): string
    {
        $states = [
            303 => 'off',
            307 => 'ok',
            455 => 'warning',
            35 => 'fault',
            51 => 'waiting',
            295 => 'updating',
            16777213 => 'grid_disconnected'
        ];
        
        return $states[$state] ?? 'unknown';
    }

    protected function validateConfig(): bool
    {
        return isset($this->config['ip_address']) && 
               isset($this->config['password']);
    }

    protected function getIdentifier(): string
    {
        return 'sma_' . str_replace('.', '_', $this->config['ip_address']);
    }

    private function analyzeSystemStatus(array $data): array
    {
        return [
            'operating_status' => $data['result']['operatingStatus'] ?? 'unknown',
            'error_count' => $data['result']['errorCount'] ?? 0,
            'warning_count' => $data['result']['warningCount'] ?? 0,
            'last_error' => $data['result']['lastError'] ?? null
        ];
    }

    private function analyzeGridQuality(array $data): array
    {
        return [
            'voltage_stability' => $data['result']['gridQuality']['voltageStability'] ?? 0,
            'frequency_stability' => $data['result']['gridQuality']['frequencyStability'] ?? 0,
            'impedance' => $data['result']['gridQuality']['impedance'] ?? 0
        ];
    }

    private function calculatePerformanceRatio(array $data): float
    {
        $actual = $data['result']['energy']['actual'] ?? 0;
        $expected = $data['result']['energy']['expected'] ?? 0;
        
        return $expected > 0 ? ($actual / $expected) * 100 : 0;
    }

    private function analyzeCommunicationQuality(array $data): array
    {
        return [
            'signal_strength' => $data['result']['communication']['signalStrength'] ?? 0,
            'packet_loss' => $data['result']['communication']['packetLoss'] ?? 0,
            'latency' => $data['result']['communication']['latency'] ?? 0
        ];
    }

    public function getAlarms(): array
    {
        try {
            $response = $this->client->post('/dyn/getValues.json', [
                'json' => [
                    'sid' => $this->sessionId,
                    'keys' => ['6100_00411200'] // Clé pour les alarmes actives
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            $alarmCodes = $data['result']['6100_00411200']['1'] ?? [];
            
            return array_map(function($alarm) {
                return [
                    'code' => $alarm['val'],
                    'message' => $this->getAlarmMessage($alarm['val']),
                    'timestamp' => $alarm['ts'] ?? time(),
                    'severity' => $this->getAlarmSeverity($alarm['val'])
                ];
            }, $alarmCodes);
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return [];
        }
    }

    public function getDeviceInfo(): array
    {
        try {
            $response = $this->client->post('/dyn/getValues.json', [
                'json' => [
                    'sid' => $this->sessionId,
                    'keys' => [
                        '6800_00811000', // Numéro de série
                        '6800_00821000', // Version du firmware
                        '6800_08822000', // Modèle
                        '6800_08823000'  // Version du matériel
                    ]
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            return [
                'manufacturer' => 'SMA',
                'model' => $data['result']['6800_08822000']['1'][0]['val'] ?? 'Unknown',
                'serial' => $data['result']['6800_00811000']['1'][0]['val'] ?? 'Unknown',
                'firmware' => $data['result']['6800_00821000']['1'][0]['val'] ?? 'Unknown',
                'hardware' => $data['result']['6800_08823000']['1'][0]['val'] ?? 'Unknown'
            ];
        } catch (GuzzleException $e) {
            $this->handleError($e);
            return [];
        }
    }

    private function getAlarmMessage(int $code): string
    {
        $messages = [
            1 => 'Défaut à la terre',
            2 => 'Défaut d\'isolement',
            3 => 'Courant de défaut',
            4 => 'Défaut onduleur',
            5 => 'Surtension DC',
            6 => 'Surtension AC',
            7 => 'Sous-tension AC',
            8 => 'Défaut réseau',
            9 => 'Fréquence réseau trop élevée',
            10 => 'Fréquence réseau trop basse',
            // Ajoutez d'autres codes d'erreur selon la documentation SMA
        ];
        
        return $messages[$code] ?? 'Erreur inconnue (Code: ' . $code . ')';
    }

    private function getAlarmSeverity(int $code): string
    {
        $highSeverityCodes = [1, 2, 3, 4];
        $mediumSeverityCodes = [5, 6, 7];
        
        if (in_array($code, $highSeverityCodes)) {
            return 'high';
        } elseif (in_array($code, $mediumSeverityCodes)) {
            return 'medium';
        }
        return 'low';
    }
}

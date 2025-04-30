<?php

namespace App\Services\Inverters;

use ModbusTcpClient\Network\BinaryStreamConnection;
use ModbusTcpClient\Network\BinaryStreamConnectionBuilder;
use ModbusTcpClient\Packet\ModbusFunction\ReadHoldingRegistersRequest;
use ModbusTcpClient\Packet\ModbusFunction\ReadHoldingRegistersResponse;
use ModbusTcpClient\Packet\ResponseFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;
use Exception;

class GoodweInverter extends BaseInverter
{
    private $client;
    private $modbusConnection;
    private $lastError;
    private $logger;

    public function __construct(array $config, LoggerInterface $logger)
    {
        parent::__construct($config);
        $this->logger = $logger;
    }

    protected function handleError($e): void
    {
        $message = $e instanceof GuzzleException 
            ? "Erreur API HTTP: " . $e->getMessage()
            : "Erreur Modbus: " . $e->getMessage();
            
        $this->lastError = $message;
        $this->connected = false;
        
        if ($this->logger) {
            $this->logger->error($message);
        }
    }

    public function connect(): bool
    {
        try {
            // Connexion HTTP pour l'API
            $this->client = new Client([
                'base_uri' => 'http://' . $this->config['ip_address'],
                'verify' => false
            ]);

            // Connexion Modbus TCP pour les données en temps réel
            $this->modbusConnection = (new BinaryStreamConnectionBuilder())
                ->setHost($this->config['ip_address'])
                ->setPort($this->config['modbus_port'])
                ->setTimeoutSec(5)
                ->setConnectTimeoutSec(2)
                ->build();

            // Test la connexion
            $request = new ReadHoldingRegistersRequest(0x0000, 1);
            $response = $this->modbusConnection->sendAndReceive($request);
            
            if (!($response instanceof ReadHoldingRegistersResponse)) {
                throw new Exception("Réponse Modbus invalide");
            }
            
            $this->connected = true;
            return true;
        } catch (GuzzleException|Exception $e) {
            $this->handleError($e);
            return false;
        }
    }

    public function disconnect(): void
    {
        if ($this->modbusConnection) {
            $this->modbusConnection->close();
        }
        $this->client = null;
        $this->modbusConnection = null;
        $this->connected = false;
    }    public function getCurrentPower(): float
    {
        try {
            // Registre 0x0484: Puissance active totale
            $packet = new ReadHoldingRegistersRequest(0x0484, 2);
            $response = $this->modbusConnection->sendAndReceive($packet);
            
            $response = $this->processModbusResponse($response);
            
            return $response->getWordAt(0)->getInt32() * 0.1; // Conversion en kW
        } catch (\Exception $e) {
            $this->handleError($e);
            return 0.0;
        }
    }    public function getDailyEnergy(): float
    {
        try {
            // Registre 0x0485: Énergie journalière
            $packet = new ReadHoldingRegistersRequest(0x0485, 2);
            $response = $this->modbusConnection->sendAndReceive($packet);
            
            $response = $this->processModbusResponse($response);
            
            return $response->getWordAt(0)->getInt32() * 0.1; // kWh
        } catch (\Exception $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getTotalEnergy(): float
    {
        try {
            // Registre 0x0486: Énergie totale
            $packet = new ReadHoldingRegistersRequest(0x0486, 2);
            $response = $this->modbusConnection->sendAndReceive($packet);
            
            $response = $this->processModbusResponse($response);
            
            return $response->getWordAt(0)->getInt32() * 0.1; // kWh
        } catch (\Exception $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getStatus(): array
    {
        try {
            // Lecture des registres pour différentes mesures
            $packet = new ReadHoldingRegistersRequest(0x0400, 20);
            $response = $this->modbusConnection->sendAndReceive($packet);
            
            $response = $this->processModbusResponse($response);
            
            return [
                'state' => $this->getOperatingState($response->getWordAt(0)->getInt16()),
                'power_dc1' => $response->getWordAt(1)->getInt32() * 0.1,
                'voltage_dc1' => $response->getWordAt(3)->getInt16() * 0.1,
                'current_dc1' => $response->getWordAt(4)->getInt16() * 0.1,
                'power_dc2' => $response->getWordAt(5)->getInt32() * 0.1,
                'voltage_dc2' => $response->getWordAt(7)->getInt16() * 0.1,
                'current_dc2' => $response->getWordAt(8)->getInt16() * 0.1,
                'power_ac' => $response->getWordAt(9)->getInt32() * 0.1,
                'voltage_ac' => $response->getWordAt(11)->getInt16() * 0.1,
                'current_ac' => $response->getWordAt(12)->getInt16() * 0.1,
                'frequency' => $response->getWordAt(13)->getInt16() * 0.01,
                'temperature' => $response->getWordAt(14)->getInt16() * 0.1,
                'efficiency' => $response->getWordAt(15)->getInt16() * 0.1
            ];
        } catch (\Exception $e) {
            $this->handleError($e);
            return [];
        }
    }

    public function getAlarms(): array
    {
        try {
            // Lecture des registres d'erreur
            $packet = new ReadHoldingRegistersRequest(0x0500, 10);
            $response = $this->modbusConnection->sendAndReceive($packet);
            
            $response = $this->processModbusResponse($response);
            
            $alarms = [];
            for ($i = 0; $i < 10; $i++) {
                $errorCode = $response->getWordAt($i)->getInt16();
                if ($errorCode > 0) {
                    $alarms[] = [
                        'code' => $errorCode,
                        'message' => $this->getErrorMessage($errorCode),
                        'timestamp' => time(),
                        'severity' => $this->getErrorSeverity($errorCode)
                    ];
                }
            }
            
            return $alarms;
        } catch (\Exception $e) {
            $this->handleError($e);
            return [];
        }
    }

    public function getDeviceInfo(): array
    {
        try {
            $response = $this->client->get('/api/device/info', [
                'query' => [
                    'sn' => $this->config['serial_number']
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            return [
                'manufacturer' => 'GoodWe',
                'model' => $data['model'] ?? '',
                'serial' => $this->config['serial_number'],
                'firmware' => $data['firmware_version'] ?? '',
                'rated_power' => $data['rated_power'] ?? '',
                'manufacture_date' => $data['manufacture_date'] ?? ''
            ];
        } catch (RequestException $e) {
            $this->handleError($e);
            return [];
        }
    }

    protected function validateConfig(): bool
    {
        return isset($this->config['ip_address']) && 
               isset($this->config['serial_number']) && 
               isset($this->config['modbus_port']);
    }

    private function getOperatingState(int $state): string
    {
        $states = [
            0 => 'waiting',
            1 => 'normal',
            2 => 'fault',
            3 => 'permanent_fault',
            4 => 'offline',
            5 => 'upgrading'
        ];
        
        return $states[$state] ?? 'unknown';
    }

    private function getErrorMessage(int $code): string
    {
        $errorMessages = [
            1 => 'Grid Over Voltage',
            2 => 'Grid Under Voltage',
            3 => 'Grid Over Frequency',
            4 => 'Grid Under Frequency',
            5 => 'PV Over Voltage',
            6 => 'Low DC Injection',
            7 => 'Temperature Protection',
            8 => 'Fan Fault',
            9 => 'Other Device Fault',
            10 => 'Grid Configuration'
        ];
        
        return $errorMessages[$code] ?? "Unknown Error (Code: $code)";
    }

    private function getErrorSeverity(int $code): string
    {
        $criticalErrors = [5, 7, 9];
        $warnings = [6, 8, 10];
        
        if (in_array($code, $criticalErrors)) {
            return 'critical';
        } elseif (in_array($code, $warnings)) {
            return 'warning';
        }
        
        return 'error';
    }

    public function controlProduction(string $action, ?float $powerLimit = null): bool
    {
        try {
            if ($action === 'start') {
                $packet = new ReadHoldingRegistersRequest(0x0483, 1);
                $this->modbusConnection->sendAndReceive($packet);
                return true;
            } elseif ($action === 'stop') {
                $packet = new ReadHoldingRegistersRequest(0x0482, 1);
                $this->modbusConnection->sendAndReceive($packet);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            $this->handleError($e);
            return false;
        }
    }

    public function getCurrentEfficiency(): float
    {
        try {
            $status = $this->getStatus();
            return $status['efficiency'] ?? 0.0;
        } catch (\Exception $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getDailyAverageEfficiency(): float
    {
        // Cette implémentation est une simplification
        return $this->getCurrentEfficiency();
    }

    public function getMonthlyAverageEfficiency(): float
    {
        // Cette implémentation est une simplification
        return $this->getCurrentEfficiency();
    }

    public function getEfficiencyFactors(): array
    {
        try {
            $status = $this->getStatus();
            return [
                'temperature' => $status['temperature'] ?? 0.0,
                'voltage_dc' => $status['voltage_dc1'] ?? 0.0,
                'current_dc' => $status['current_dc1'] ?? 0.0
            ];
        } catch (\Exception $e) {
            $this->handleError($e);
            return [];
        }
    }

    public function getLastMaintenanceDate(): ?\DateTime
    {
        // À implémenter avec une vraie base de données
        return null;
    }

    public function getNextMaintenanceDate(): ?\DateTime
    {
        // À implémenter avec une vraie base de données
        return null;
    }

    public function getMaintenanceHistory(): array
    {
        // À implémenter avec une vraie base de données
        return [];
    }

    public function getRecommendedMaintenanceActions(): array
    {
        try {
            $alarms = $this->getAlarms();
            $actions = [];
            
            foreach ($alarms as $alarm) {
                if ($alarm['severity'] === 'critical') {
                    $actions[] = "Maintenance requise : " . $alarm['message'];
                }
            }
            
            return $actions;
        } catch (\Exception $e) {
            $this->handleError($e);
            return [];
        }
    }

    public function reset(string $type): bool
    {
        try {
            if ($type === 'error') {
                $packet = new ReadHoldingRegistersRequest(0x0481, 1);
                $this->modbusConnection->sendAndReceive($packet);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            $this->handleError($e);
            return false;
        }
    }

    public function runDiagnostics(): array
    {
        try {
            $status = $this->getStatus();
            $alarms = $this->getAlarms();
            
            return [
                'status' => $status,
                'alarms' => $alarms,
                'connection_status' => $this->connected,
                'last_error' => $this->lastError ?? null
            ];
        } catch (\Exception $e) {
            $this->handleError($e);
            return [];
        }
    }

    public function updateConfiguration(array $settings): array
    {
        // À implémenter avec les registres Modbus appropriés
        return [
            'success' => false,
            'message' => 'Non implémenté'
        ];
    }

    public function updateFirmware(string $firmwarePath, string $version): array
    {
        // À implémenter avec l'API HTTP appropriée
        return [
            'success' => false,
            'message' => 'Non implémenté'
        ];
    }

    private function processModbusResponse($response)
    {
        if (is_string($response)) {
            $response = ResponseFactory::parseResponse($response);
        }
        
        if (!$response || !method_exists($response, 'getWordAt')) {
            throw new \Exception('Invalid Modbus response');
        }
        
        return $response;
    }
}

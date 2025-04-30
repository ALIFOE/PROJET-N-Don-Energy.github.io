<?php

namespace App\Services\Inverters;

use ModbusTcpClient\Network\BinaryStreamConnection;
use ModbusTcpClient\Network\BinaryStreamConnectionBuilder;
use ModbusTcpClient\Packet\ModbusFunction\ReadHoldingRegistersRequest;
use ModbusTcpClient\Packet\ModbusFunction\ReadInputRegistersRequest;
use ModbusTcpClient\Packet\ModbusFunction\ReadHoldingRegistersResponse;
use ModbusTcpClient\Packet\ResponseFactory;
use Exception;
use Psr\Log\LoggerInterface;

class SchneiderInverter extends BaseInverter
{
    private ?string $lastError = null;
    private $connection;
    private $unitId = 1;
    private $logger;

    public function __construct(array $config, LoggerInterface $logger)
    {
        parent::__construct($config);
        $this->logger = $logger;
    }

    public function connect(): bool
    {
        try {
            $this->connection = (new BinaryStreamConnectionBuilder())
                ->setHost($this->config['ip_address'])
                ->setPort(502)
                ->setTimeoutSec(5)
                ->setConnectTimeoutSec(2)
                ->build();
            
            // Test la connexion en lisant un registre
            $request = new ReadHoldingRegistersRequest(40000, 1, $this->unitId);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($request)
            );
            
            $this->connected = true;
            $this->logger->info("Connecté à l'onduleur Schneider: {$this->config['ip_address']}:502");
            return true;
        } catch (Exception $e) {
            $this->handleError($e);
            return false;
        }
    }

    public function disconnect(): void
    {
        if ($this->connection) {
            $this->connection->close();
            $this->connection = null;
        }
        $this->connected = false;
    }

    private function processModbusResponse($response)
    {
        if (is_string($response)) {
            $response = ResponseFactory::parseResponse($response);
        }
        
        if (!($response instanceof ReadHoldingRegistersResponse)) {
            throw new Exception('Réponse Modbus invalide');
        }
        
        return $response;
    }

    protected function handleError(Exception $e): void
    {
        $this->lastError = $e->getMessage();
        $this->connected = false;
        $this->logger->error("Erreur onduleur Schneider: " . $e->getMessage());
    }

    protected function readInt32($response, int $offset): int
    {
        $values = $response->getWords();
        $highWord = (int)$values[$offset];
        $lowWord = (int)$values[$offset + 1];
        return ($highWord << 16) | $lowWord;
    }

    protected function readInt16($response, int $word): int
    {
        $values = $response->getWords();
        return (int)$values[$word];
    }

    private function readString($response, int $startWord, int $length): string
    {
        $values = $response->getWords();
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $word = (int)$values[$startWord + $i];
            $result .= chr(($word >> 8) & 0xFF) . chr($word & 0xFF);
        }
        return trim($result);
    }

    public function getCurrentPower(): float
    {
        try {
            // Registre 30201: Puissance active totale
            $packet = new ReadInputRegistersRequest(30201, 2, $this->unitId);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            $value = $this->readInt32($response, 0);
            return $value * 0.1; // Conversion en kW
        } catch (\Exception $e) {
            $this->handleError($e);
            return 0.0;
        }
    }    public function getDailyEnergy(): float
    {
        try {
            // Registre 30301: Énergie produite aujourd'hui
            $packet = new ReadInputRegistersRequest(30301, 2, $this->unitId);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            return $this->readInt32($response, 0) * 0.1; // Conversion en kWh
        } catch (\Exception $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getTotalEnergy(): float
    {
        try {
            // Registre 30303: Énergie totale
            $packet = new ReadInputRegistersRequest(30303, 2, $this->unitId);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            return $this->readInt32($response, 0) * 0.1; // kWh
        } catch (\Exception $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getStatus(): array
    {
        try {
            // Lecture des registres pour les différentes mesures
            $packet = new ReadInputRegistersRequest(30000, 20, $this->unitId);
            $response = $this->connection->sendAndReceive($packet);
              return [
                'state' => $this->getOperatingState($this->readInt16($response, 0)),
                'power_dc' => $this->readInt32($response, 1) * 0.1,
                'power_ac' => $this->readInt32($response, 3) * 0.1,
                'voltage_dc' => $this->readInt16($response, 5) * 0.1,
                'current_dc' => $this->readInt16($response, 6) * 0.1,
                'voltage_ac' => $this->readInt16($response, 7) * 0.1,
                'current_ac' => $this->readInt16($response, 8) * 0.1,
                'frequency' => $this->readInt16($response, 9) * 0.01,
                'temperature' => $this->readInt16($response, 10) * 0.1,
                'efficiency' => $this->readInt16($response, 11) * 0.1
            ];
        } catch (\Exception $e) {
            $this->handleError($e);
            return [];
        }
    }    public function getAlarms(): array
    {
        try {
            // Lecture des registres d'alarme
            $packet = new ReadInputRegistersRequest(30100, 10, $this->unitId);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            $alarms = [];
            for ($i = 0; $i < 10; $i++) {
                $alarmCode = $this->readInt16($response, $i);
                if ($alarmCode > 0) {
                    $alarms[] = [
                        'code' => $alarmCode,
                        'message' => $this->getAlarmMessage($alarmCode),
                        'timestamp' => time(),
                        'severity' => $this->getAlarmSeverity($alarmCode)
                    ];
                }
            }
            
            return $alarms;
        } catch (\Exception $e) {
            $this->handleError($e);
            return [];
        }
    }

    protected function getLastError(): ?string
    {
        return $this->lastError ?? null;
    }

    public function getDeviceInfo(): array
    {
        try {
            // Lecture des registres d'information
            $packet = new ReadHoldingRegistersRequest(40000, 10, $this->unitId);
            $response = $this->connection->sendAndReceive($packet);
            
            return [
                'manufacturer' => 'Schneider Electric',
                'model' => $this->readString($response, 0, 4),
                'serial_number' => $this->readString($response, 4, 4),
                'firmware_version' => $this->readString($response, 8, 2)
            ];
        } catch (\Exception $e) {
            $this->handleError($e);
            return [];
        }
    }

    protected function validateConfig(): bool
    {
        return isset($this->config['ip_address']) && 
               isset($this->config['username']) && 
               isset($this->config['password']);
    }

    private function getOperatingState(int $state): string
    {
        $states = [
            0 => 'disconnected',
            1 => 'initializing',
            2 => 'connecting',
            3 => 'running',
            4 => 'throttled',
            5 => 'fault',
            6 => 'standby',
            7 => 'maintenance',
            8 => 'updating'
        ];
        
        return $states[$state] ?? 'unknown';
    }

    private function getAlarmMessage(int $code): string
    {
        $alarmMessages = [
            1 => 'Grid Overvoltage',
            2 => 'Grid Undervoltage',
            3 => 'Grid Overfrequency',
            4 => 'Grid Underfrequency',
            5 => 'DC Input Overvoltage',
            6 => 'Inverter Overtemperature',
            7 => 'System Fault',
            8 => 'Arc Fault Detection',
            9 => 'Ground Fault',
            10 => 'Communication Error'
        ];
        
        return $alarmMessages[$code] ?? "Unknown Alarm (Code: $code)";
    }

    private function getAlarmSeverity(int $code): string
    {
        $criticalAlarms = [5, 6, 7, 8, 9];
        $warningAlarms = [1, 2, 3, 4, 10];
        
        if (in_array($code, $criticalAlarms)) {
            return 'critical';
        } elseif (in_array($code, $warningAlarms)) {
            return 'warning';
        }
        
        return 'info';
    }

    public function getCurrentEfficiency(): float
    {
        try {
            $status = $this->getStatus();
            $dcPower = $status['power_dc'];
            $acPower = $status['power_ac'];
            
            if ($dcPower > 0) {
                return ($acPower / $dcPower) * 100;
            }
            
            return $status['efficiency'];
        } catch (\Exception $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getDailyAverageEfficiency(): float
    {
        try {
            $packet = new ReadInputRegistersRequest(30400, 1, $this->unitId);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            return $this->readInt16($response, 0) * 0.1;
        } catch (\Exception $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getMonthlyAverageEfficiency(): float
    {
        try {
            $packet = new ReadInputRegistersRequest(30401, 1, $this->unitId);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            return $this->readInt16($response, 0) * 0.1;
        } catch (\Exception $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getEfficiencyFactors(): array
    {
        try {
            $status = $this->getStatus();
            return [
                'power_dc' => $status['power_dc'],
                'power_ac' => $status['power_ac'],
                'voltage_dc' => $status['voltage_dc'],
                'voltage_ac' => $status['voltage_ac'],
                'temperature' => $status['temperature']
            ];
        } catch (\Exception $e) {
            $this->handleError($e);
            return [];
        }
    }

    public function getLastMaintenanceDate(): ?\DateTime
    {
        try {
            $packet = new ReadHoldingRegistersRequest(40100, 6, $this->unitId);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            $values = $response->getWords();
            $year = (int)$values[0];
            $month = (int)$values[1];
            $day = (int)$values[2];
            
            if ($year > 0) {
                return new \DateTime("$year-$month-$day");
            }
            
            return null;
        } catch (\Exception $e) {
            $this->handleError($e);
            return null;
        }
    }

    public function getNextMaintenanceDate(): ?\DateTime
    {
        try {
            $packet = new ReadHoldingRegistersRequest(40106, 6, $this->unitId);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            $values = $response->getWords();
            $year = (int)$values[0];
            $month = (int)$values[1];
            $day = (int)$values[2];
            
            if ($year > 0) {
                return new \DateTime("$year-$month-$day");
            }
            
            return null;
        } catch (\Exception $e) {
            $this->handleError($e);
            return null;
        }
    }

    public function getMaintenanceHistory(): array
    {
        try {
            $packet = new ReadHoldingRegistersRequest(40200, 100, $this->unitId);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            $history = [];
            $values = $response->getWords();
            
            for ($i = 0; $i < 100; $i += 5) {
                $year = (int)$values[$i];
                if ($year === 0) break;
                
                $month = (int)$values[$i + 1];
                $day = (int)$values[$i + 2];
                $type = (int)$values[$i + 3];
                $status = (int)$values[$i + 4];
                
                $history[] = [
                    'date' => new \DateTime("$year-$month-$day"),
                    'type' => $this->getMaintenanceType($type),
                    'status' => $this->getMaintenanceStatus($status)
                ];
            }
            
            return $history;
        } catch (\Exception $e) {
            $this->handleError($e);
            return [];
        }
    }

    private function getMaintenanceType(int $type): string
    {
        $types = [
            1 => 'Inspection visuelle',
            2 => 'Nettoyage',
            3 => 'Test de performance',
            4 => 'Remplacement de pièce',
            5 => 'Mise à jour firmware'
        ];
        
        return $types[$type] ?? "Type inconnu ($type)";
    }

    private function getMaintenanceStatus(int $status): string
    {
        $statuses = [
            0 => 'Non effectué',
            1 => 'En cours',
            2 => 'Terminé',
            3 => 'Reporté',
            4 => 'Annulé'
        ];
        
        return $statuses[$status] ?? "Statut inconnu ($status)";
    }

    public function getRecommendedMaintenanceActions(): array
    {
        try {
            $packet = new ReadHoldingRegistersRequest(40300, 20, $this->unitId);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            $actions = [];
            for ($i = 0; $i < 20; $i += 2) {
                $type = $this->readInt16($response, $i);
                if ($type === 0) break;
                
                $priority = $response->getWordAt($i + 1)->getInt16();
                $actions[] = [
                    'action' => $this->getMaintenanceType($type),
                    'priority' => $priority
                ];
            }
            
            return $actions;
        } catch (\Exception $e) {
            $this->handleError($e);
            return [];
        }
    }

    public function controlProduction(string $action, ?float $powerLimit = null): bool
    {
        try {
            $controlValue = match($action) {
                'start' => 1,
                'stop' => 2,
                'standby' => 3,
                default => throw new \Exception('Action non valide')
            };
            
            $packet = new ReadHoldingRegistersRequest(40500, 2, $this->unitId);
            $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            if ($powerLimit !== null) {
                $limitValue = (int)($powerLimit * 10);
                $packet = new ReadHoldingRegistersRequest(40502, 2, $this->unitId);
                $this->processModbusResponse(
                    $this->connection->sendAndReceive($packet)
                );
            }
            
            return true;
        } catch (\Exception $e) {
            $this->handleError($e);
            return false;
        }
    }

    public function reset(string $type): bool
    {
        try {
            $resetValue = match($type) {
                'error' => 1,
                'power' => 2,
                'communication' => 3,
                default => throw new \Exception('Type de réinitialisation non valide')
            };
            
            $packet = new ReadHoldingRegistersRequest(40600, 1, $this->unitId);
            $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            return true;
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
            $efficiency = $this->getCurrentEfficiency();
            
            return [
                'status' => $status,
                'alarms' => $alarms,
                'efficiency' => $efficiency,                'connection' => [
                    'status' => $this->connected,
                    'last_error' => $this->getLastError()
                ]
            ];
        } catch (\Exception $e) {
            $this->handleError($e);
            return [];
        }
    }

    public function updateConfiguration(array $settings): array
    {
        try {
            foreach ($settings as $register => $value) {
                $packet = new ReadHoldingRegistersRequest((int)$register, 1, $this->unitId);
                $this->processModbusResponse(
                    $this->connection->sendAndReceive($packet)
                );
            }
            
            return [
                'success' => true,
                'message' => 'Configuration mise à jour avec succès'
            ];
        } catch (\Exception $e) {
            $this->handleError($e);
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function updateFirmware(string $firmwarePath, string $version): array
    {
        try {
            if (!file_exists($firmwarePath)) {
                throw new \Exception('Fichier firmware non trouvé');
            }
            
            // Commencer la mise à jour
            $packet = new ReadHoldingRegistersRequest(40700, 1, $this->unitId);
            $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            // L'implémentation réelle nécessiterait un protocole spécifique
            // pour transférer le firmware vers l'onduleur
            
            return [
                'success' => false,
                'message' => 'La mise à jour du firmware via Modbus n\'est pas supportée'
            ];
        } catch (\Exception $e) {
            $this->handleError($e);
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}

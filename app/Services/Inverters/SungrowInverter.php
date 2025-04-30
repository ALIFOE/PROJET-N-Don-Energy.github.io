<?php

namespace App\Services\Inverters;

use ModbusTcpClient\Network\BinaryStreamConnection;
use ModbusTcpClient\Network\BinaryStreamConnectionBuilder;
use ModbusTcpClient\Packet\ModbusFunction\ReadHoldingRegistersRequest;
use ModbusTcpClient\Packet\ModbusFunction\ReadHoldingRegistersResponse;
use ModbusTcpClient\Packet\ResponseFactory;
use Exception;

class SungrowInverter extends BaseInverter
{
    private $connection;
    private $unitId = 1;

    public function connect(): bool
    {
        try {
            $this->connection = (new BinaryStreamConnectionBuilder())
                ->setHost($this->config['host'])
                ->setPort($this->config['port'])
                ->setTimeoutSec(5)
                ->setConnectTimeoutSec(2)
                ->build();

            // Test la connexion
            $request = new ReadHoldingRegistersRequest(40000, 1, $this->unitId);
            $response = $this->connection->sendAndReceive($request);
            
            if (!($response instanceof ReadHoldingRegistersResponse)) {
                throw new Exception("Réponse Modbus invalide");
            }
            
            $this->connected = true;
            return true;
        } catch (Exception $e) {
            $this->handleError($e);
            return false;
        }
    }

    protected function validateConfig(): bool
    {
        return isset($this->config['host']) && 
               isset($this->config['port']);
    }

    public function getCurrentPower(): float
    {
        try {
            $startAddress = 40000;
            $quantity = 2;
            
            $packet = new ReadHoldingRegistersRequest($startAddress, $quantity);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            $values = $response->getWords();
            return (int)$values[0] * 0.1; // Conversion en kW
        } catch (Exception $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getStatus(): array
    {
        try {
            $startAddress = 40000;
            $quantity = 10;
            
            $packet = new ReadHoldingRegistersRequest($startAddress, $quantity);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            $values = $response->getWords();
            return [
                'state' => (int)$values[0],
                'voltage' => (int)$values[1] * 0.1,
                'current' => (int)$values[2] * 0.1,
                'frequency' => (int)$values[3] * 0.01,
                'power' => (int)$values[4] * 0.1,
                'temperature' => (int)$values[5] * 0.1,
                'daily_energy' => (int)$values[6] * 0.1,
                'total_energy' => ((int)$values[7] << 16 | (int)$values[8]) * 0.1
            ];
        } catch (Exception $e) {
            $this->handleError($e);
            return [];
        }
    }

    private function getMaintenanceType(int $type): string
    {
        return match($type) {
            1 => 'Inspection régulière',
            2 => 'Nettoyage',
            3 => 'Remplacement de composant',
            4 => 'Mise à jour firmware',
            5 => 'Calibration',
            default => 'Maintenance non spécifiée'
        };
    }

    public function getRecommendedMaintenanceActions(): array
    {
        try {
            $packet = new ReadHoldingRegistersRequest(40400, 10);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            $values = $response->getWords();
            $actions = [];
            
            for ($i = 0; $i < 10; $i += 2) {
                $actionType = (int)$values[$i];
                if ($actionType === 0) break;
                
                $priority = (int)$values[$i + 1];
                $actions[] = [
                    'type' => $this->getMaintenanceType($actionType),
                    'priority' => $priority,
                    'recommended_date' => (new \DateTime())->modify('+' . ($priority * 30) . ' days')
                ];
            }
            
            return $actions;
        } catch (Exception $e) {
            $this->handleError($e);
            return [];
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
                'connection' => [
                    'connected' => $this->isConnected(),
                    'host' => $this->config['host'],
                    'port' => $this->config['port']
                ],
                'maintenance' => [
                    'last_date' => $this->getLastMaintenanceDate(),
                    'next_date' => $this->getNextMaintenanceDate(),
                    'recommended_actions' => $this->getRecommendedMaintenanceActions()
                ],
                'efficiency' => [
                    'current' => $this->getCurrentEfficiency(),
                    'daily_average' => $this->getDailyAverageEfficiency(),
                    'monthly_average' => $this->getMonthlyAverageEfficiency(),
                    'factors' => $this->getEfficiencyFactors()
                ]
            ];
        } catch (Exception $e) {
            $this->handleError($e);
            return [];
        }
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

    public function disconnect(): void
    {
        if ($this->connection) {
            $this->connection->close();
            $this->connected = false;
        }
    }

    public function getDailyEnergy(): float
    {
        try {
            $startAddress = 40070;
            $quantity = 2;
            
            $packet = new ReadHoldingRegistersRequest($startAddress, $quantity);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            $values = $response->getWords();
            return ((int)$values[0] << 16 | (int)$values[1]) * 0.01; // Conversion en kWh
        } catch (Exception $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getTotalEnergy(): float
    {
        try {
            $startAddress = 40072;
            $quantity = 2;
            
            $packet = new ReadHoldingRegistersRequest($startAddress, $quantity);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            $values = $response->getWords();
            return ((int)$values[0] << 16 | (int)$values[1]) * 0.1; // Conversion en kWh
        } catch (Exception $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getAlarms(): array
    {
        try {
            $startAddress = 40100;
            $quantity = 10;
            
            $packet = new ReadHoldingRegistersRequest($startAddress, $quantity);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            $values = $response->getWords();
            $alarms = [];
            for ($i = 0; $i < $quantity; $i++) {
                $alarmCode = (int)$values[$i];
                if ($alarmCode > 0) {
                    $alarms[] = [
                        'code' => $alarmCode,
                        'message' => $this->getAlarmMessage($alarmCode),
                        'severity' => $this->getAlarmSeverity($alarmCode),
                        'timestamp' => time()
                    ];
                }
            }
            
            return $alarms;
        } catch (Exception $e) {
            $this->handleError($e);
            return [];
        }
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

    private function getAlarmMessage(int $code): string
    {
        $alarmCodes = [
            1 => 'Surtension réseau',
            2 => 'Sous-tension réseau',
            3 => 'Surcharge',
            4 => 'Surchauffe',
            5 => 'Court-circuit PV',
            6 => 'Défaut d\'isolement',
            7 => 'Erreur de communication',
            8 => 'Fréquence hors plage',
            9 => 'Erreur ventilateur',
            10 => 'Défaut interne'
        ];
        
        return $alarmCodes[$code] ?? "Alarme inconnue (code: $code)";
    }

    private function getAlarmSeverity(int $code): string
    {
        $criticalCodes = [3, 4, 5, 6];
        $warningCodes = [7, 8, 9];
        
        if (in_array($code, $criticalCodes)) {
            return 'critical';
        } elseif (in_array($code, $warningCodes)) {
            return 'warning';
        }
        return 'info';
    }

    public function getDeviceInfo(): array
    {
        try {
            $packet = new ReadHoldingRegistersRequest(30000, 10, $this->unitId);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            $values = $response->getWords();
            
            return [
                'manufacturer' => 'Sungrow',
                'model' => $this->readString($response, 0, 4),
                'serial' => $this->readString($response, 4, 4),
                'firmware' => sprintf('%d.%d.%d',
                    (int)$values[8],
                    (int)$values[9],
                    (int)$values[10]
                )
            ];
        } catch (Exception $e) {
            $this->handleError($e);
            return [];
        }
    }

    private function readDeviceModel(): string
    {
        $packet = new ReadHoldingRegistersRequest(30000, 2, $this->unitId);
        $response = $this->processModbusResponse(
            $this->connection->sendAndReceive($packet)
        );
        
        return $this->readString($response, 0, 2);
    }

    private function readSerialNumber(): string
    {
        $packet = new ReadHoldingRegistersRequest(30002, 8, $this->unitId);
        $response = $this->processModbusResponse(
            $this->connection->sendAndReceive($packet)
        );
        
        return $this->readString($response, 0, 8);
    }

    private function readFirmwareVersion(): string
    {
        $packet = new ReadHoldingRegistersRequest(30010, 3, $this->unitId);
        $response = $this->processModbusResponse(
            $this->connection->sendAndReceive($packet)
        );
        
        $values = $response->getWords();
        return sprintf('%d.%d.%d',
            (int)$values[0],
            (int)$values[1],
            (int)$values[2]
        );
    }

    public function getCurrentEfficiency(): float
    {
        try {
            $status = $this->getStatus();
            if (isset($status['power_dc']) && $status['power_dc'] > 0) {
                return ($status['power_ac'] / $status['power_dc']) * 100;
            }
            return 0.0;
        } catch (\Exception $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getDailyAverageEfficiency(): float
    {
        // Cette implémentation est simplifiée, idéalement devrait utiliser une base de données
        return $this->getCurrentEfficiency();
    }

    public function getMonthlyAverageEfficiency(): float
    {
        // Cette implémentation est simplifiée, idéalement devrait utiliser une base de données
        return $this->getCurrentEfficiency();
    }

    public function getEfficiencyFactors(): array
    {
        try {
            $status = $this->getStatus();
            return [
                'temperature' => $status['temperature'] ?? 0.0,
                'voltage_dc' => $status['voltage_dc'] ?? 0.0,
                'voltage_ac' => $status['voltage_ac'] ?? 0.0
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
                $packet = new ReadHoldingRegistersRequest((int)$register, 1);
                $this->connection->sendAndReceive($packet);
            }
            return ['success' => true, 'message' => 'Configuration mise à jour'];
        } catch (\Exception $e) {
            $this->handleError($e);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateFirmware(string $firmwarePath, string $version): array
    {
        // Sungrow ne supporte pas la mise à jour du firmware via Modbus
        return [
            'success' => false,
            'message' => 'La mise à jour du firmware doit être effectuée via le logiciel SolarInfo Browser'
        ];
    }

    public function reset(string $type): bool
    {
        try {
            $resetValue = match($type) {
                'error' => 1,
                'power' => 2,
                'factory' => 3,
                default => throw new \Exception('Type de réinitialisation non valide')
            };
            
            $packet = new ReadHoldingRegistersRequest(40500, 1);
            $this->connection->sendAndReceive($packet);
            return true;
        } catch (\Exception $e) {
            $this->handleError($e);
            return false;
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
            
            $packet = new ReadHoldingRegistersRequest(40200, 1);
            $this->connection->sendAndReceive($packet);
            
            if ($powerLimit !== null) {
                $limitPacket = new ReadHoldingRegistersRequest(40201, 2);
                $this->connection->sendAndReceive($limitPacket);
            }
            
            return true;
        } catch (\Exception $e) {
            $this->handleError($e);
            return false;
        }
    }

    public function getLastMaintenanceDate(): ?\DateTime
    {
        try {
            $packet = new ReadHoldingRegistersRequest(40300, 3, $this->unitId);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            $values = $response->getWords();
            if ((int)$values[0] === 0) {
                return null;
            }
            
            return new \DateTime(sprintf(
                '%d-%d-%d',
                (int)$values[0],
                (int)$values[1],
                (int)$values[2]
            ));
        } catch (\Exception $e) {
            $this->handleError($e);
            return null;
        }
    }

    public function getNextMaintenanceDate(): ?\DateTime
    {
        try {
            $packet = new ReadHoldingRegistersRequest(40320, 3, $this->unitId);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            $values = $response->getWords();
            if ((int)$values[0] === 0) {
                return null;
            }
            
            return new \DateTime(sprintf(
                '%d-%d-%d',
                (int)$values[0],
                (int)$values[1],
                (int)$values[2]
            ));
        } catch (\Exception $e) {
            $this->handleError($e);
            return null;
        }
    }

    public function getMaintenanceHistory(): array
    {
        try {
            $packet = new ReadHoldingRegistersRequest(40310, 20, $this->unitId);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            $values = $response->getWords();
            $history = [];
            for ($i = 0; $i < 20; $i += 4) {
                $type = (int)$values[$i];
                if ($type === 0) continue;
                
                $history[] = [
                    'type' => $this->getMaintenanceType($type),
                    'date' => sprintf(
                        '%d-%d-%d',
                        (int)$values[$i + 1],
                        (int)$values[$i + 2],
                        (int)$values[$i + 3]
                    )
                ];
            }
            
            return $history;
        } catch (\Exception $e) {
            $this->handleError($e);
            return [];
        }
    }
}

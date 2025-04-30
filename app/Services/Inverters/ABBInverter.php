<?php

namespace App\Services\Inverters;

use ModbusTcpClient\Network\BinaryStreamConnection;
use ModbusTcpClient\Network\BinaryStreamConnectionBuilder;
use ModbusTcpClient\Packet\ModbusFunction\ReadHoldingRegistersRequest;
use ModbusTcpClient\Packet\ModbusFunction\ReadInputRegistersRequest;
use ModbusTcpClient\Packet\ModbusFunction\ReadHoldingRegistersResponse;
use ModbusTcpClient\Packet\ResponseFactory;
use Exception;

class ABBInverter extends BaseInverter
{
    private $connection;
    private $unitId = 1;

    public function connect(): bool
    {
        try {
            $this->connection = (new BinaryStreamConnectionBuilder())
                ->setHost($this->config['ip_address'])
                ->setPort($this->config['port'])
                ->setTimeoutSec(5)
                ->setConnectTimeoutSec(2)
                ->build();

            // Test de connexion en lisant un registre
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
        }
        $this->connection = null;
        $this->connected = false;
    }

    protected function readInt32($response, int $offset): int
    {
        $highWord = $response->getWords()[$offset];
        $lowWord = $response->getWords()[$offset + 1];
        return ($highWord << 16) | $lowWord;
    }

    public function getCurrentPower(): float
    {
        try {
            // Registre 40100: Puissance active totale (W)
            $packet = new ReadInputRegistersRequest(40100, 2, $this->unitId);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            return $this->readInt32($response, 0) * 0.1; // Conversion en kW
        } catch (\Exception $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getDailyEnergy(): float
    {
        try {
            // Registre 40200: Énergie journalière (Wh)
            $packet = new ReadInputRegistersRequest(40200, 2, $this->unitId);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            return $this->readInt32($response, 0) * 0.001; // Conversion en kWh
        } catch (\Exception $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getTotalEnergy(): float
    {
        try {
            // Registre 40300: Énergie totale (kWh)
            $packet = new ReadInputRegistersRequest(40300, 2, $this->unitId);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            return $this->readInt32($response, 0) * 0.1;
        } catch (\Exception $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getStatus(): array
    {
        try {
            // Lecture des registres pour différentes mesures
            $packet = new ReadInputRegistersRequest(40000, 10, $this->unitId);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            $words = $response->getWords();
            return [
                'state' => $this->getOperatingState((int)$words[0]),
                'power' => $this->readInt32($response, 1) * 0.1,
                'voltage_dc1' => (int)$words[3] * 0.1,
                'current_dc1' => (int)$words[4] * 0.1,
                'voltage_dc2' => (int)$words[5] * 0.1,
                'current_dc2' => (int)$words[6] * 0.1,
                'voltage_ac' => (int)$words[7] * 0.1,
                'current_ac' => (int)$words[8] * 0.1,
                'frequency' => (int)$words[9] * 0.01
            ];
        } catch (\Exception $e) {
            $this->handleError($e);
            return [];
        }
    }

    public function getAlarms(): array
    {
        try {
            // Lecture des registres d'alarme
            $packet = new ReadInputRegistersRequest(45000, 10, $this->unitId);
            $response = $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );
            
            $alarms = [];
            for ($i = 0; $i < 10; $i++) {
                $alarmCode = $response->getWordAt($i)->getInt16();
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

    public function getDeviceInfo(): array
    {
        try {
            // Lecture des registres d'information
            $packet = new ReadHoldingRegistersRequest(30000, 10, $this->unitId);
            $response = $this->connection->sendAndReceive($packet);
            
            return [
                'manufacturer' => 'ABB',
                'model' => $this->readString($response, 0, 4),
                'serial' => $this->config['serial_number'],
                'firmware' => $this->readString($response, 4, 2)
            ];
        } catch (\Exception $e) {
            $this->handleError($e);
            return [];
        }
    }

    protected function validateConfig(): bool
    {
        return isset($this->config['ip_address']) && 
               isset($this->config['port']) && 
               isset($this->config['serial_number']);
    }

    private function getOperatingState(int $state): string
    {
        $states = [
            0 => 'off',
            1 => 'sleeping',
            2 => 'starting',
            3 => 'mppt',
            4 => 'throttled',
            5 => 'shutting_down',
            6 => 'fault',
            7 => 'standby',
            8 => 'no_dc_power'
        ];
        
        return $states[$state] ?? 'unknown';
    }

    private function getAlarmMessage(int $code): string
    {
        $alarmMessages = [
            1 => 'Grid Voltage Error',
            2 => 'Grid Frequency Error',
            3 => 'Internal Error',
            4 => 'Temperature Too High',
            5 => 'PV Voltage Too High',
            6 => 'Fan Failure',
            7 => 'Ground Fault',
            8 => 'DC Injection High',
            9 => 'Grid Current DC Offset',
            10 => 'Inverter Overload'
        ];
        
        return $alarmMessages[$code] ?? "Unknown Alarm (Code: $code)";
    }

    private function getAlarmSeverity(int $code): string
    {
        $criticalAlarms = [3, 4, 7];
        $warningAlarms = [6, 8, 9, 10];
        
        if (in_array($code, $criticalAlarms)) {
            return 'critical';
        } elseif (in_array($code, $warningAlarms)) {
            return 'warning';
        }
        
        return 'info';
    }

    private function readString($response, int $startWord, int $length): string
    {
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $word = $response->getWordAt($startWord + $i)->getInt16();
            $result .= chr(($word >> 8) & 0xFF) . chr($word & 0xFF);
        }
        return trim($result);
    }

    public function controlProduction(string $action, ?float $powerLimit = null): bool
    {
        try {
            $controlValue = $action === 'start' ? 1 : 0;
            $packet = new ReadHoldingRegistersRequest(40400, 1, $this->unitId);
            $this->processModbusResponse(
                $this->connection->sendAndReceive($packet)
            );

            if ($powerLimit !== null) {
                $powerLimitValue = (int)($powerLimit * 10); // Conversion en W
                $packet = new ReadHoldingRegistersRequest(40401, 2, $this->unitId);
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

    public function getCurrentEfficiency(): float
    {
        try {
            $status = $this->getStatus();
            if ($status['power'] > 0 && isset($status['power_dc1']) && isset($status['power_dc2'])) {
                $dcPower = $status['power_dc1'] + $status['power_dc2'];
                return ($status['power'] / $dcPower) * 100;
            }
            return 0.0;
        } catch (\Exception $e) {
            $this->handleError($e);
            return 0.0;
        }
    }

    public function getDailyAverageEfficiency(): float
    {
        // Pour une implémentation complète, il faudrait stocker les valeurs d'efficacité dans une base de données
        return $this->getCurrentEfficiency();
    }

    public function getMonthlyAverageEfficiency(): float
    {
        // Pour une implémentation complète, il faudrait stocker les valeurs d'efficacité dans une base de données
        return $this->getCurrentEfficiency();
    }

    public function getEfficiencyFactors(): array
    {
        try {
            $status = $this->getStatus();
            return [
                'voltage_dc' => ($status['voltage_dc1'] + $status['voltage_dc2']) / 2,
                'current_dc' => ($status['current_dc1'] + $status['current_dc2']) / 2,
                'voltage_ac' => $status['voltage_ac'],
                'current_ac' => $status['current_ac'],
                'frequency' => $status['frequency']
            ];
        } catch (\Exception $e) {
            $this->handleError($e);
            return [];
        }
    }

    public function getLastMaintenanceDate(): ?\DateTime
    {
        // Cette méthode nécessiterait une base de données pour stocker l'historique de maintenance
        return null;
    }

    public function getNextMaintenanceDate(): ?\DateTime
    {
        // Cette méthode nécessiterait une base de données pour stocker le planning de maintenance
        return null;
    }

    public function getMaintenanceHistory(): array
    {
        // Cette méthode nécessiterait une base de données pour stocker l'historique de maintenance
        return [];
    }

    public function getRecommendedMaintenanceActions(): array
    {
        try {
            $alarms = $this->getAlarms();
            $actions = [];
            
            foreach ($alarms as $alarm) {
                if ($alarm['severity'] === 'critical') {
                    $actions[] = 'Maintenance urgente requise : ' . $alarm['message'];
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
                $packet = new ReadHoldingRegistersRequest(40500, 1, $this->unitId);
                $this->processModbusResponse(
                    $this->connection->sendAndReceive($packet)
                );
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
                'connection' => $this->connected,
                'efficiency' => $this->getCurrentEfficiency()
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
                'message' => 'Erreur lors de la mise à jour de la configuration'
            ];
        }
    }

    public function updateFirmware(string $firmwarePath, string $version): array
    {
        // L'onduleur ABB ne supporte pas la mise à jour du firmware via Modbus
        return [
            'success' => false,
            'message' => 'La mise à jour du firmware n\'est pas supportée via cette interface'
        ];
    }
}

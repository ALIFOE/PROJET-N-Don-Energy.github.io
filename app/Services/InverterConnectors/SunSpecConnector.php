<?php

namespace App\Services\InverterConnectors;

use ModbusTcpClient\Network\BinaryStreamConnection;
use ModbusTcpClient\Network\BinaryStreamConnectionBuilder;
use ModbusTcpClient\Packet\ModbusFunction\ReadHoldingRegistersRequest;
use ModbusTcpClient\Packet\ModbusFunction\ReadHoldingRegistersResponse;
use Exception;

class SunSpecConnector extends BaseInverterConnector
{
    protected $client;
    protected $host;
    protected $port;
    protected $baseRegister = 40000;
    protected $models = [];

    public function connect(): bool
    {
        try {
            $this->host = $this->config['host'] ?? $this->inverter->ip_address;
            $this->port = $this->config['port'] ?? $this->inverter->port;
            
            $this->client = (new BinaryStreamConnectionBuilder())
                ->setHost($this->host)
                ->setPort($this->port)
                ->setTimeoutSec(5)
                ->build();

            if (!$this->verifySunSpecSignature()) {
                throw new Exception("Signature SunSpec invalide");
            }

            $this->models = $this->discoverModels();
            return true;
        } catch (Exception $e) {
            throw new Exception("Erreur de connexion à l'onduleur: " . $e->getMessage());
        }
    }

    public function disconnect(): bool
    {
        if ($this->client) {
            $this->client->close();
        }
        $this->connected = false;
        return true;
    }

    public function getRealtimeData(): array
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        try {
            $commonBlock = $this->readModel(101); // Modèle commun des onduleurs
            $inverterBlock = $this->readModel(103); // Modèle des onduleurs triphasés
            
            $data = [
                'power' => $this->scaleValue($inverterBlock['W'], $inverterBlock['W_SF']),
                'daily_energy' => $this->scaleValue($commonBlock['WH'], $commonBlock['WH_SF']) / 1000,
                'total_energy' => $this->scaleValue($commonBlock['WH_TOT'], $commonBlock['WH_SF']) / 1000,
                'voltage_ac' => $this->scaleValue($inverterBlock['PhVphA'], $inverterBlock['V_SF']),
                'current_ac' => $this->scaleValue($inverterBlock['A'], $inverterBlock['A_SF']),
                'frequency' => $this->scaleValue($inverterBlock['Hz'], $inverterBlock['Hz_SF']),
                'temperature' => $this->scaleValue($inverterBlock['TmpCab'], $inverterBlock['Tmp_SF']),
                'status' => $this->getSunSpecStatus($commonBlock['St'])
            ];
            
            $this->saveReading($data);
            return $data;
        } catch (Exception $e) {
            $this->logger->error("Erreur lors de la lecture SunSpec: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    public function getHistoricalData(string $startDate, string $endDate): array
    {
        // SunSpec ne définit pas de modèle pour les données historiques
        // On utilise donc les données stockées en base
        return \App\Models\InverterReading::where('inverter_id', $this->inverter->id)
            ->whereBetween('read_at', [$startDate, $endDate])
            ->get()
            ->map(function ($reading) {
                return [
                    'timestamp' => $reading->read_at,
                    'data' => json_decode($reading->data, true)
                ];
            })
            ->toArray();
    }

    public function getDeviceInfo(): array
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        try {
            $commonBlock = $this->readModel(1); // Modèle d'identification
            
            return [
                'manufacturer' => trim($this->bytesToString($commonBlock['Mn'])),
                'model' => trim($this->bytesToString($commonBlock['Md'])),
                'serial' => trim($this->bytesToString($commonBlock['SN'])),
                'version' => trim($this->bytesToString($commonBlock['Vr'])),
                'supported_models' => array_keys($this->models)
            ];
        } catch (Exception $e) {
            $this->logger->error("Erreur lors de la lecture des infos SunSpec: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    protected function verifySunSpecSignature(): bool
    {
        $request = new ReadHoldingRegistersRequest($this->baseRegister, 2, 1);
        $response = $this->client->sendAndReceive($request);
        if (!$response instanceof ReadHoldingRegistersResponse) {
            return false;
        }
        
        $values = $response->getWords();
        $signature = pack('n*', (int)$values[0], (int)$values[1]);
        return $signature === "SunS";
    }

    protected function readModel(int $modelId): array
    {
        if (!isset($this->models[$modelId])) {
            throw new Exception("Modèle SunSpec $modelId non supporté par cet onduleur");
        }
        
        $model = $this->models[$modelId];
        $request = new ReadHoldingRegistersRequest($model['start'], $model['length'], 1);
        $response = $this->client->sendAndReceive($request);
        if (!$response instanceof ReadHoldingRegistersResponse) {
            throw new Exception("Réponse Modbus invalide");
        }
        
        $words = $response->getWords();
        $values = [];
        foreach ($words as $index => $word) {
            $values[$index] = (int)$word;
        }
        return $values;
    }

    protected function discoverModels(): array
    {
        $models = [];
        $offset = $this->baseRegister + 2;
        
        while (true) {
            try {
                $request = new ReadHoldingRegistersRequest($offset, 2, 1);
                $response = $this->client->sendAndReceive($request);
                if (!$response instanceof ReadHoldingRegistersResponse) {
                    break;
                }
                
                $words = $response->getWords();
                $modelId = (int)$words[0];
                $length = (int)$words[1];
                
                if ($modelId === 0xFFFF) {
                    break;
                }
                
                $models[$modelId] = [
                    'start' => $offset,
                    'length' => $length
                ];
                
                $offset += $length + 2;
            } catch (Exception $e) {
                break;
            }
        }
        
        return $models;
    }

    protected function scaleValue($value, $scaleFactor): float
    {
        if ($value === null || $scaleFactor === null) {
            return 0.0;
        }
        return $value * pow(10, $scaleFactor);
    }

    protected function bytesToString(array $bytes): string
    {
        return implode('', array_map(function($value) {
            return chr(($value >> 8) & 0xFF) . chr($value & 0xFF);
        }, $bytes));
    }

    protected function getSunSpecStatus(int $status): string
    {
        $statuses = [
            1 => 'off',
            2 => 'sleeping',
            3 => 'starting',
            4 => 'mppt',
            5 => 'throttled',
            6 => 'shutting_down',
            7 => 'fault',
            8 => 'standby',
        ];
        
        return $statuses[$status] ?? 'unknown';
    }
}
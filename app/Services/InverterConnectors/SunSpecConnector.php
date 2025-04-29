<?php

namespace App\Services\InverterConnectors;

use Exception;
use ModbusTcpClient\Composer\Read\ReadRegistersBuilder;
use ModbusTcpClient\Network\BinaryStreamConnection;

class SunSpecConnector extends BaseInverterConnector
{
    protected $client;
    protected $baseRegister = 40000; // Registre de base SunSpec
    protected $models = [];
    
    public function connect(): bool
    {
        try {
            $this->host = $this->config['host'] ?? $this->inverter->ip_address;
            $this->port = $this->config['port'] ?? $this->inverter->port;
            
            $this->client = new BinaryStreamConnection([
                'host' => $this->host,
                'port' => $this->port,
                'timeoutSec' => 5
            ]);
            
            // Vérifier la signature SunSpec
            if (!$this->verifySunSpecSignature()) {
                throw new Exception("Signature SunSpec non trouvée");
            }
            
            // Lire les modèles disponibles
            $this->models = $this->discoverModels();
            
            $this->connected = true;
            $this->logger->info("Connecté à l'onduleur SunSpec: {$this->host}:{$this->port}");
            return true;
        } catch (Exception $e) {
            $this->logger->error("Erreur de connexion SunSpec: " . $e->getMessage());
            $this->connected = false;
            return false;
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
        $builder = new ReadRegistersBuilder();
        $builder->readHoldingRegisters(1, $this->baseRegister, 2);
        
        $response = $builder->build()->sendTo($this->client);
        $values = $response[0]->getData();
        
        $signature = pack('n*', $values[0], $values[1]);
        return $signature === "SunS";
    }

    protected function discoverModels(): array
    {
        $models = [];
        $offset = $this->baseRegister + 2;
        
        while (true) {
            try {
                $builder = new ReadRegistersBuilder();
                $builder->readHoldingRegisters(1, $offset, 2);
                
                $response = $builder->build()->sendTo($this->client);
                $values = $response[0]->getData();
                
                if ($values[0] === 0xFFFF) {
                    break;
                }
                
                $modelId = $values[0];
                $length = $values[1];
                
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

    protected function readModel(int $modelId): array
    {
        if (!isset($this->models[$modelId])) {
            throw new Exception("Modèle SunSpec $modelId non supporté par cet onduleur");
        }
        
        $model = $this->models[$modelId];
        $builder = new ReadRegistersBuilder();
        $builder->readHoldingRegisters(1, $model['start'], $model['length']);
        
        $response = $builder->build()->sendTo($this->client);
        return $response[0]->getData();
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
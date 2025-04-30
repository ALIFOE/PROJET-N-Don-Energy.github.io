<?php

namespace App\Services\InverterConnectors;

use Exception;
use ModbusTcpClient\Packet\ModbusFunction\ReadHoldingRegistersRequest;
use ModbusTcpClient\Packet\ModbusFunction\ReadHoldingRegistersResponse;
use ModbusTcpClient\Network\BinaryStreamConnection;
use ModbusTcpClient\Network\BinaryStreamConnectionBuilder;

class ModbusTcpConnector extends BaseInverterConnector
{
    protected $client;
    protected $host;
    protected $port;
    protected $unitId;
    protected $timeout;
    protected $retries = 3;

    public function connect(): bool
    {
        try {
            $this->host = $this->config['host'] ?? $this->inverter->ip_address;
            $this->port = $this->config['port'] ?? 502;
            $this->unitId = $this->config['unit_id'] ?? 1;
            $this->timeout = $this->config['timeout'] ?? 10;

            if (!$this->host) {
                throw new Exception("Hôte Modbus TCP non configuré");
            }

            if (!$this->testPort()) {
                throw new Exception("Le port {$this->port} n'est pas accessible. Vérifiez votre pare-feu.");
            }

            $this->client = (new BinaryStreamConnectionBuilder())
                ->setHost($this->host)
                ->setPort($this->port)
                ->setTimeoutSec($this->timeout)
                ->setConnectTimeoutSec(5)
                ->build();

            // Test de la connexion avec une requête simple
            $request = new ReadHoldingRegistersRequest(0, 1, $this->unitId);
            $response = $this->client->sendAndReceive($request);
            
            if ($response instanceof ReadHoldingRegistersResponse) {
                $this->connected = true;
                $this->logger->info("Connecté à l'onduleur via Modbus TCP: {$this->host}:{$this->port}");
                return true;
            }
            
            throw new Exception("Échec du test de connexion Modbus TCP");
        } catch (Exception $e) {
            $this->logger->error("Erreur de connexion Modbus TCP: " . $e->getMessage());
            $this->connected = false;
            throw $e;
        }
    }

    protected function testPort(): bool
    {
        for ($i = 0; $i < $this->retries; $i++) {
            try {
                $socket = @fsockopen($this->host, $this->port, $errno, $errstr, 2);
                if ($socket) {
                    fclose($socket);
                    return true;
                }
                // Petite pause entre les tentatives
                if ($i < $this->retries - 1) sleep(1);
            } catch (\Exception $e) {
                $this->logger->warning("Tentative " . ($i + 1) . "/" . $this->retries . " échouée : " . $e->getMessage());
            }
        }
        return false;
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
            // Lecture des données via Modbus
            $request = new ReadHoldingRegistersRequest(30001, 10, $this->unitId);
            $response = $this->client->sendAndReceive($request);

            if (!$response instanceof ReadHoldingRegistersResponse) {
                throw new Exception("Réponse Modbus invalide");
            }

            $values = $response->getWords();
            $rawValues = array_map(fn($word) => (int)$word, $values);

            $data = [
                'power' => $this->convertValue($rawValues[0], 1), // W
                'daily_energy' => $this->convertValue($rawValues[1], 10), // kWh
                'total_energy' => $this->convertValue($rawValues[2], 10), // kWh
                'voltage_ac' => $this->convertValue($rawValues[3], 10), // V
                'current_ac' => $this->convertValue($rawValues[4], 100), // A
                'frequency' => $this->convertValue($rawValues[5], 100), // Hz
                'temperature' => $this->convertValue($rawValues[6], 10), // °C
            ];

            return $data;
        } catch (Exception $e) {
            $this->logger->error("Erreur lors de la lecture des données Modbus: " . $e->getMessage());
            throw $e;
        }
    }

    public function getHistoricalData(string $startDate, string $endDate): array
    {
        // Modbus ne stocke généralement pas de données historiques
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
            $request = new ReadHoldingRegistersRequest(40000, 10, $this->unitId);
            $response = $this->client->sendAndReceive($request);
            
            if (!$response instanceof ReadHoldingRegistersResponse) {
                throw new Exception("Réponse Modbus invalide");
            }
            
            $values = $response->getWords();
            $rawValues = array_map(fn($word) => (int)$word, $values);
            
            return [
                'device_type' => 'Modbus TCP Device',
                'vendor' => $this->inverter->manufacturer,
                'model' => $this->inverter->model,
                'serial' => $this->extractSerialNumber($rawValues),
                'firmware' => $this->extractFirmwareVersion($rawValues),
                'connection' => "Modbus TCP {$this->host}:{$this->port}"
            ];
        } catch (Exception $e) {
            $this->logger->error("Erreur lors de la lecture des infos appareil: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    protected function convertValue($value, float $factor): float
    {
        if ($value === null || $value === 0x8000) { // Valeur non implémentée
            return 0;
        }
        return $value / $factor;
    }

    protected function getStatus(int $statusCode): string
    {
        $statuses = [
            0 => 'offline',
            1 => 'standby',
            2 => 'startup',
            3 => 'production',
            4 => 'shutdown',
            5 => 'fault',
        ];
        
        return $statuses[$statusCode] ?? 'unknown';
    }

    protected function extractSerialNumber(array $values): string
    {
        // Les 4 premiers registres contiennent généralement le numéro de série
        return implode('', array_map(function($value) {
            return chr(($value >> 8) & 0xFF) . chr($value & 0xFF);
        }, array_slice($values, 0, 4)));
    }

    protected function extractFirmwareVersion(array $values): string
    {
        // Le registre 5 contient généralement la version du firmware
        $major = ($values[4] >> 8) & 0xFF;
        $minor = $values[4] & 0xFF;
        return "{$major}.{$minor}";
    }
}
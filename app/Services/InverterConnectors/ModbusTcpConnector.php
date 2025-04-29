<?php

namespace App\Services\InverterConnectors;

use Exception;
use ModbusTcpClient\Composer\Read\ReadRegistersBuilder;
use ModbusTcpClient\Network\BinaryStreamConnection;

class ModbusTcpConnector extends BaseInverterConnector
{
    protected $client;
    protected $host;
    protected $port;
    protected $unitId;
    protected $timeout;

    public function connect(): bool
    {
        try {
            $this->host = $this->config['host'] ?? null;
            $this->port = $this->config['port'] ?? 502;
            $this->unitId = $this->config['unit_id'] ?? 1;
            $this->timeout = $this->config['timeout'] ?? 5;

            if (!$this->host) {
                throw new Exception("Hôte Modbus TCP non configuré");
            }

            // La connexion réelle est établie lors de l'envoi de la première requête
            $this->client = BinaryStreamConnection::getBuilder()
                ->setHost($this->host)
                ->setPort($this->port)
                ->setTimeoutSec($this->timeout)
                ->build();

            $this->connected = true;
            $this->logger->info("Connecté à l'onduleur via Modbus TCP: {$this->host}:{$this->port}");
            return true;
        } catch (Exception $e) {
            $this->logger->error("Erreur de connexion Modbus TCP: " . $e->getMessage());
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
            $builder = new ReadRegistersBuilder();
            
            // Registres standards pour les données en temps réel
            $builder->readHoldingRegisters($this->unitId, 30001, 10);
            
            $responses = $builder->build()->sendTo($this->client);
            $values = $responses[0]->getData();
            
            $data = [
                'power' => $this->convertValue($values[0], 1), // W
                'daily_energy' => $this->convertValue($values[1], 10), // kWh
                'total_energy' => $this->convertValue($values[2], 10), // kWh
                'voltage_ac' => $this->convertValue($values[3], 10), // V
                'current_ac' => $this->convertValue($values[4], 100), // A
                'frequency' => $this->convertValue($values[5], 100), // Hz
                'temperature' => $this->convertValue($values[6], 10), // °C
                'status' => $this->getStatus($values[7])
            ];
            
            $this->saveReading($data);
            return $data;
        } catch (Exception $e) {
            $this->logger->error("Erreur lors de la lecture Modbus TCP: " . $e->getMessage());
            return ['error' => $e->getMessage()];
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
            $builder = new ReadRegistersBuilder();
            $builder->readHoldingRegisters($this->unitId, 40000, 10);
            
            $responses = $builder->build()->sendTo($this->client);
            $values = $responses[0]->getData();
            
            return [
                'device_type' => 'Modbus TCP Device',
                'vendor' => $this->inverter->manufacturer,
                'model' => $this->inverter->model,
                'serial' => $this->extractSerialNumber($values),
                'firmware' => $this->extractFirmwareVersion($values),
                'connection' => "Modbus TCP {$this->host}:{$this->port}"
            ];
        } catch (Exception $e) {
            $this->logger->error("Erreur lors de la lecture des infos appareil: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    protected function convertValue($value, float $factor): float
    {
        if ($value === 0x8000) { // Valeur non implémentée
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
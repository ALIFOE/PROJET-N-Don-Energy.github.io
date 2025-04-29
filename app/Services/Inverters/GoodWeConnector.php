<?php

namespace App\Services\Inverters;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GoodWeConnector implements InverterConnectorInterface
{
    private string $ipAddress;
    private string $serialNumber;
    private int $modbusPort;

    public function __construct(string $ipAddress, string $serialNumber, int $modbusPort = 502)
    {
        $this->ipAddress = $ipAddress;
        $this->serialNumber = $serialNumber;
        $this->modbusPort = $modbusPort;
    }

    public function fetchRealTimeData(): array
    {
        $cacheKey = 'goodwe_inverter_realtime_data';
        
        return Cache::remember($cacheKey, 60, function () {
            try {
                $client = new \ModbusTcpClient\Network\BinaryStreamConnection(
                    $this->ipAddress,
                    $this->modbusPort,
                    5.0
                );

                $data = [];
                
                // Lecture des registres Modbus pour les données en temps réel
                $registers = $client->connect()->readHoldingRegisters(0, 50);
                
                if ($registers) {
                    $data = [
                        'current_power' => $this->calculatePower($registers),
                        'daily_energy' => $this->getDailyProduction(),
                        'status' => $this->getStatus(),
                        'timestamp' => now()->timestamp,
                        'dc_voltage' => $this->calculateDCVoltage($registers),
                        'ac_voltage' => $this->calculateACVoltage($registers),
                        'temperature' => $this->calculateTemperature($registers)
                    ];
                }

                $client->close();
                return $data;
            } catch (\Exception $e) {
                \Log::error("Erreur de connexion à l'onduleur GoodWe: " . $e->getMessage());
                return [
                    'current_power' => 0,
                    'daily_energy' => 0,
                    'status' => 3,
                    'timestamp' => now()->timestamp
                ];
            }
        });
    }

    private function calculatePower($registers): float
    {
        // Registres spécifiques à GoodWe pour la puissance active
        return ($registers[16] * 256 + $registers[17]) / 10;
    }

    private function calculateDCVoltage($registers): float
    {
        return ($registers[4] * 256 + $registers[5]) / 10;
    }

    private function calculateACVoltage($registers): float
    {
        return ($registers[12] * 256 + $registers[13]) / 10;
    }

    private function calculateTemperature($registers): float
    {
        return ($registers[32] * 256 + $registers[33]) / 10;
    }

    public function getDailyProduction(): float
    {
        try {
            $client = new \ModbusTcpClient\Network\BinaryStreamConnection(
                $this->ipAddress,
                $this->modbusPort,
                5.0
            );

            $registers = $client->connect()->readHoldingRegisters(60, 2);
            $client->close();

            if ($registers) {
                // Conversion des registres en valeur d'énergie journalière
                return ($registers[0] * 256 + $registers[1]) / 10;
            }

            return 0.0;
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la récupération de la production journalière GoodWe: " . $e->getMessage());
            return 0.0;
        }
    }

    public function getStatus(): int
    {
        try {
            $client = new \ModbusTcpClient\Network\BinaryStreamConnection(
                $this->ipAddress,
                $this->modbusPort,
                5.0
            );

            $registers = $client->connect()->readHoldingRegisters(200, 1);
            $client->close();

            if ($registers) {
                $status = $registers[0];
                return match ($status) {
                    0 => 0, // Normal
                    1, 2 => 1, // Warning
                    3, 4 => 2, // Error
                    default => 3
                };
            }

            return 3;
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la récupération du statut GoodWe: " . $e->getMessage());
            return 3;
        }
    }
}
<?php

namespace App\Factories;

use App\Services\Inverters\InverterConnectorInterface;
use App\Services\Inverters\SunGrowConnector;
use App\Services\Inverters\HuaweiConnector;
use App\Services\Inverters\SMAConnector;
use App\Services\Inverters\FroniusConnector;
use App\Services\Inverters\SchneiderConnector;
use App\Services\Inverters\ABBConnector;
use App\Services\Inverters\DeltaConnector;
use App\Services\Inverters\GoodWeConnector;
use App\Services\InverterDetectionService;
use App\Services\Inverters\InverterDetectionServiceInterface;
use App\Services\Inverters\InverterDetectionService as DefaultInverterDetectionService;

class InverterConnectorFactory
{
    private InverterDetectionService $detectionService;

    public function __construct(InverterDetectionService $detectionService)
    {
        $this->detectionService = $detectionService;
    }

    public function createConnector(): InverterConnectorInterface
    {
        // Tente de détecter automatiquement l'onduleur
        $inverterType = $this->detectionService->detectInverter();
        
        return match ($inverterType) {
            'sungrow' => new SunGrowConnector(
                config('inverters.connections.sungrow.host'),
                config('inverters.connections.sungrow.port')
            ),
            'huawei' => new HuaweiConnector(
                config('inverters.connections.huawei.api_url'),
                config('inverters.connections.huawei.api_key')
            ),
            'sma' => new SMAConnector(
                config('inverters.connections.sma.ip_address'),
                config('inverters.connections.sma.password')
            ),
            'fronius' => new FroniusConnector(
                config('inverters.connections.fronius.ip_address'),
                config('inverters.connections.fronius.device_id')
            ),
            'schneider' => new SchneiderConnector(
                config('inverters.connections.schneider.ip_address'),
                config('inverters.connections.schneider.username'),
                config('inverters.connections.schneider.password')
            ),
            'abb' => new ABBConnector(
                config('inverters.connections.abb.ip_address'),
                config('inverters.connections.abb.port'),
                config('inverters.connections.abb.serial_number')
            ),
            'delta' => new DeltaConnector(
                config('inverters.connections.delta.ip_address'),
                config('inverters.connections.delta.username'),
                config('inverters.connections.delta.password')
            ),
            'goodwe' => new GoodWeConnector(
                config('inverters.connections.goodwe.ip_address'),
                config('inverters.connections.goodwe.serial_number'),
                config('inverters.connections.goodwe.modbus_port')
            ),
            default => throw new \InvalidArgumentException("Type d'onduleur non supporté: {$inverterType}")
        };
    }
}

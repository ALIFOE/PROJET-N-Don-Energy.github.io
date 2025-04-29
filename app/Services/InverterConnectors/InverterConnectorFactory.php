<?php

namespace App\Services\InverterConnectors;

use App\Models\Inverter;
use Exception;
use Illuminate\Support\Str;

class InverterConnectorFactory
{
    private static $connectorMap = [
        'modbus_tcp' => ModbusTcpConnector::class,
        'sunspec' => SunSpecConnector::class,
        'rest_api' => RestApiConnector::class
    ];

    private static $brandConnectorMap = [
        'sma' => 'modbus_tcp',
        'fronius' => 'rest_api',
        'solaredge' => 'rest_api',
        'huawei' => 'modbus_tcp',
        'goodwe' => 'modbus_tcp',
        'solax' => 'modbus_tcp',
        'growatt' => 'modbus_tcp',
        'victron' => 'modbus_tcp'
    ];

    public static function create(Inverter $inverter): InverterConnectorInterface
    {
        // Détermine le type de connexion basé sur la marque si non spécifié
        if (!$inverter->connection_type) {
            $brand = Str::lower($inverter->brand);
            $inverter->connection_type = self::$brandConnectorMap[$brand] ?? 'modbus_tcp';
            $inverter->save();
        }

        // Obtient la classe du connecteur
        $connectorClass = self::$connectorMap[$inverter->connection_type] ?? null;
        
        if (!$connectorClass || !class_exists($connectorClass)) {
            throw new Exception("Type de connecteur non supporté: {$inverter->connection_type}");
        }

        return new $connectorClass($inverter);
    }

    public static function getAvailableConnectors(): array
    {
        return array_keys(self::$connectorMap);
    }

    public static function getDefaultConnectorForBrand(string $brand): string
    {
        return self::$brandConnectorMap[Str::lower($brand)] ?? 'modbus_tcp';
    }
}
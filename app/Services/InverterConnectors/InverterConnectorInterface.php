<?php

namespace App\Services\InverterConnectors;

interface InverterConnectorInterface
{
    public function connect(): bool;
    public function disconnect(): bool;
    public function isConnected(): bool;
    public function getRealtimeData(): array;
    public function getHistoricalData(string $startDate, string $endDate): array;
    public function getDeviceInfo(): array;
}
<?php

namespace App\Services\Inverters;

interface InverterInterface
{
    // Méthodes de base existantes
    public function connect(): bool;
    public function disconnect(): void;
    public function getCurrentPower(): float;
    public function getDailyEnergy(): float;
    public function getTotalEnergy(): float;
    public function getStatus(): array;
    public function getAlarms(): array;
    public function getDeviceInfo(): array;

    // Nouvelles méthodes pour l'efficacité
    public function getCurrentEfficiency(): float;
    public function getDailyAverageEfficiency(): float;
    public function getMonthlyAverageEfficiency(): float;
    public function getEfficiencyFactors(): array;

    // Configuration et mise à jour
    public function updateConfiguration(array $settings): array;
    public function updateFirmware(string $firmwarePath, string $version): array;
    public function reset(string $type): bool;

    // Contrôle de production
    public function controlProduction(string $action, ?float $powerLimit = null): bool;

    // Diagnostics
    public function runDiagnostics(): array;

    // Maintenance
    public function getLastMaintenanceDate(): ?\DateTime;
    public function getNextMaintenanceDate(): ?\DateTime;
    public function getMaintenanceHistory(): array;
    public function getRecommendedMaintenanceActions(): array;
}

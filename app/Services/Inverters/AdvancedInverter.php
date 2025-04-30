<?php

namespace App\Services\Inverters;

use App\Models\InverterHistory;
use Carbon\Carbon;

abstract class AdvancedInverter extends BaseInverter
{
    public function getCurrentEfficiency(): float
    {
        $status = $this->getStatus();
        if (isset($status['power_dc']) && $status['power_dc'] > 0 && isset($status['power_ac'])) {
            return ($status['power_ac'] / $status['power_dc']) * 100;
        }
        return 0.0;
    }

    public function getDailyAverageEfficiency(): float
    {
        $today = Carbon::today();
        $records = InverterHistory::where('inverter_name', $this->getIdentifier())
            ->whereDate('timestamp', $today)
            ->whereNotNull('efficiency')
            ->avg('efficiency');
        
        return (float) ($records ?? 0.0);
    }

    public function getMonthlyAverageEfficiency(): float
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $records = InverterHistory::where('inverter_name', $this->getIdentifier())
            ->where('timestamp', '>=', $startOfMonth)
            ->whereNotNull('efficiency')
            ->avg('efficiency');
        
        return (float) ($records ?? 0.0);
    }

    public function getEfficiencyFactors(): array
    {
        $status = $this->getStatus();
        return [
            'temperature_impact' => $this->calculateTemperatureImpact($status['temperature'] ?? null),
            'voltage_deviation' => $this->calculateVoltageDeviation(
                $status['voltage_dc'] ?? null,
                $status['voltage_ac'] ?? null
            ),
            'current_balance' => $this->calculateCurrentBalance(
                $status['current_dc'] ?? null,
                $status['current_ac'] ?? null
            )
        ];
    }

    public function updateConfiguration(array $settings): array
    {
        // Validation de base des paramètres
        $validSettings = array_intersect_key($settings, array_flip([
            'power_limit',
            'grid_voltage',
            'grid_frequency',
            'operation_mode',
            'communication_params'
        ]));

        if (empty($validSettings)) {
            throw new \InvalidArgumentException('Aucun paramètre de configuration valide fourni');
        }

        return $this->applyConfiguration($validSettings);
    }

    public function reset(string $type): bool
    {
        switch ($type) {
            case 'soft':
                return $this->performSoftReset();
            case 'hard':
                return $this->performHardReset();
            case 'factory':
                return $this->performFactoryReset();
            default:
                throw new \InvalidArgumentException("Type de réinitialisation non supporté: $type");
        }
    }

    public function getLastMaintenanceDate(): ?\DateTime
    {
        // Récupérer la dernière maintenance depuis l'historique
        $lastMaintenance = $this->getMaintenanceHistory()[0] ?? null;
        return $lastMaintenance ? new \DateTime($lastMaintenance['date']) : null;
    }

    public function getNextMaintenanceDate(): ?\DateTime
    {
        $lastDate = $this->getLastMaintenanceDate();
        if (!$lastDate) {
            return null;
        }

        // Par défaut, programmer la prochaine maintenance 6 mois après la dernière
        return $lastDate->modify('+6 months');
    }

    public function getMaintenanceHistory(): array
    {
        // À implémenter par chaque onduleur spécifique
        return [];
    }

    public function getRecommendedMaintenanceActions(): array
    {
        $status = $this->getStatus();
        $alarms = $this->getAlarms();
        $actions = [];

        // Vérifier la température
        if (isset($status['temperature']) && $status['temperature'] > 70) {
            $actions[] = [
                'priority' => 'high',
                'action' => 'Vérifier le système de refroidissement',
                'reason' => 'Température élevée détectée'
            ];
        }

        // Vérifier l'efficacité
        if ($this->getCurrentEfficiency() < 90) {
            $actions[] = [
                'priority' => 'medium',
                'action' => 'Inspecter les panneaux solaires et les connexions',
                'reason' => 'Efficacité sous-optimale'
            ];
        }

        // Ajouter des actions basées sur les alarmes
        foreach ($alarms as $alarm) {
            if ($alarm['severity'] === 'critical') {
                $actions[] = [
                    'priority' => 'high',
                    'action' => "Résoudre l'alarme: " . $alarm['message'],
                    'reason' => 'Alarme critique active'
                ];
            }
        }

        return $actions;
    }

    protected function calculateTemperatureImpact(?float $temperature): float
    {
        if ($temperature === null) {
            return 0.0;
        }

        // Impact de la température sur l'efficacité
        // Température optimale : 25°C
        $optimalTemp = 25.0;
        $impactPerDegree = 0.004; // 0.4% par degré d'écart

        return 1.0 - (abs($temperature - $optimalTemp) * $impactPerDegree);
    }

    protected function calculateVoltageDeviation(?float $voltageDC, ?float $voltageAC): float
    {
        if ($voltageDC === null || $voltageAC === null) {
            return 0.0;
        }

        // Calcul de la déviation par rapport aux valeurs nominales
        $nominalDC = 600.0; // Tension DC nominale
        $nominalAC = 230.0; // Tension AC nominale

        $dcDeviation = abs(($voltageDC - $nominalDC) / $nominalDC);
        $acDeviation = abs(($voltageAC - $nominalAC) / $nominalAC);

        return 1.0 - (($dcDeviation + $acDeviation) / 2);
    }

    protected function calculateCurrentBalance(?float $currentDC, ?float $currentAC): float
    {
        if ($currentDC === null || $currentAC === null || $currentDC === 0) {
            return 0.0;
        }

        // Calcul du ratio de conversion courant DC/AC
        return min(1.0, $currentAC / $currentDC);
    }

    abstract protected function applyConfiguration(array $settings): array;
    abstract protected function performSoftReset(): bool;
    abstract protected function performHardReset(): bool;
    abstract protected function performFactoryReset(): bool;
    abstract protected function getIdentifier(): string;
}

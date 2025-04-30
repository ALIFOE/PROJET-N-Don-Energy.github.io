<?php

namespace App\Console\Commands;

use App\Services\Inverters\InverterManager;
use App\Models\InverterHistory;
use Illuminate\Console\Command;

class CollectInverterData extends Command
{
    protected $signature = 'inverters:collect-data';
    protected $description = 'Collecte les données de tous les onduleurs configurés';

    private $inverterManager;

    public function __construct(InverterManager $inverterManager)
    {
        parent::__construct();
        $this->inverterManager = $inverterManager;
    }

    public function handle()
    {
        $inverters = $this->inverterManager->supportedInverters();

        foreach ($inverters as $inverterName) {
            try {
                $inverter = $this->inverterManager->connect($inverterName);
                
                $status = $inverter->getStatus();
                $power = $inverter->getCurrentPower();
                $efficiency = method_exists($inverter, 'getCurrentEfficiency') ? 
                    $inverter->getCurrentEfficiency() : null;

                InverterHistory::create([
                    'inverter_name' => $inverterName,
                    'timestamp' => now(),
                    'power' => $power,
                    'energy' => $inverter->getDailyEnergy(),
                    'voltage_dc' => $status['voltage_dc'] ?? null,
                    'current_dc' => $status['current_dc'] ?? null,
                    'voltage_ac' => $status['voltage_ac'] ?? null,
                    'current_ac' => $status['current_ac'] ?? null,
                    'frequency' => $status['frequency'] ?? null,
                    'temperature' => $status['temperature'] ?? null,
                    'efficiency' => $efficiency,
                    'additional_data' => array_diff_key($status, array_flip([
                        'voltage_dc', 'current_dc', 'voltage_ac', 'current_ac',
                        'frequency', 'temperature'
                    ]))
                ]);

                $this->info("Données collectées pour l'onduleur: $inverterName");
            } catch (\Exception $e) {
                $this->error("Erreur lors de la collecte des données pour l'onduleur $inverterName: " . $e->getMessage());
            } finally {
                if (isset($inverter)) {
                    $inverter->disconnect();
                }
            }
        }

        $this->info('Collecte des données terminée');
    }
}

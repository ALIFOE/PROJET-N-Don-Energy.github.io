<?php

namespace App\Console\Commands;

use App\Models\Inverter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchInverterDataCommand extends Command
{
    protected $signature = 'inverters:fetch-data {inverter?}';
    protected $description = 'Récupère les données des onduleurs connectés';

    public function handle()
    {
        $inverterId = $this->argument('inverter');

        if ($inverterId) {
            $inverters = Inverter::where('id', $inverterId)->get();
        } else {
            $inverters = Inverter::where('status', 'connected')->get();
        }

        foreach ($inverters as $inverter) {
            $this->fetchData($inverter);
        }
    }

    protected function fetchData(Inverter $inverter)
    {
        try {
            $this->info("Récupération des données de l'onduleur #{$inverter->id}...");
            
            if (!$inverter->connect()) {
                $this->error("Impossible de se connecter à l'onduleur #{$inverter->id}");
                return;
            }

            $data = $inverter->getRealtimeData();
            
            if (!isset($data['error'])) {
                $this->info("Données récupérées avec succès pour l'onduleur #{$inverter->id}");
                $this->line("  Puissance: {$data['power']} W");
                $this->line("  Énergie journalière: {$data['daily_energy']} kWh");
                $this->line("  Température: {$data['temperature']} °C");
                $this->line("  État: {$data['status']}");
            } else {
                $this->error("Erreur lors de la récupération des données: {$data['error']}");
            }

            $inverter->disconnect();
        } catch (\Exception $e) {
            Log::error("Erreur lors de la récupération des données de l'onduleur #{$inverter->id}: " . $e->getMessage());
            $this->error($e->getMessage());
        }
    }
}
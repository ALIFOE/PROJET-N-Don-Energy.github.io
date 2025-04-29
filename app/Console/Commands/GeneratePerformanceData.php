<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Installation;
use App\Models\DonneeProduction;
use App\Models\DonneeMeteo;
use Carbon\Carbon;

class GeneratePerformanceData extends Command
{
    protected $signature = 'performance:generate';
    protected $description = 'Génère des données de performance simulées pour les installations';

    public function handle()
    {
        $installations = Installation::where('pays', 'Togo')->get();
        $now = Carbon::now();

        foreach ($installations as $installation) {
            // Simuler des données de production
            DonneeProduction::create([
                'installation_id' => $installation->id,
                'date_heure' => $now,
                'puissance_instantanee' => $this->generatePower($now->hour),
                'energie_jour' => rand(10, 50),
                'energie_mois' => rand(300, 1500),
                'energie_annee' => rand(3600, 18000),
                'energie_totale' => rand(7200, 36000),
                'rendement' => rand(75, 95)
            ]);

            // Simuler des données météo
            DonneeMeteo::create([
                'installation_id' => $installation->id,
                'date_heure' => $now,
                'temperature' => rand(20, 35),
                'humidite' => rand(40, 80),
                'vitesse_vent' => rand(0, 20),
                'direction_vent' => rand(0, 360),
                'irradiation' => $this->generateIrradiation($now->hour),
                'ensoleillement' => rand(0, 12)
            ]);
        }

        $this->info('Données de performance générées avec succès.');
    }

    private function generatePower($hour)
    {
        // Simulation réaliste de la production selon l'heure
        if ($hour < 6 || $hour > 18) {
            return 0;
        }
        
        $maxPower = 5000; // 5kW pic
        $factor = sin(M_PI * ($hour - 6) / 12);
        return max(0, $maxPower * $factor * (0.8 + (rand(0, 40) / 100)));
    }

    private function generateIrradiation($hour)
    {
        // Simulation réaliste de l'irradiation selon l'heure
        if ($hour < 6 || $hour > 18) {
            return 0;
        }
        
        $maxIrradiation = 1000; // 1000 W/m²
        $factor = sin(M_PI * ($hour - 6) / 12);
        return max(0, $maxIrradiation * $factor * (0.8 + (rand(0, 40) / 100)));
    }
}
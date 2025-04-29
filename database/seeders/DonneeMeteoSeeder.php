<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DonneeMeteo;
use App\Models\Installation;
use Carbon\Carbon;

class DonneeMeteoSeeder extends Seeder
{
    public function run()
    {
        // Récupérer toutes les installations
        $installations = Installation::all();

        foreach ($installations as $installation) {
            // Génération de données pour les dernières 24 heures
            for ($i = 24; $i >= 0; $i--) {
                $time = Carbon::now()->subHours($i);
                
                // Variation sinusoïdale de la température pour simuler le cycle jour/nuit
                $baseTemp = 24; // température moyenne
                $amplitude = 5; // variation de ±5°C
                $temperature = $baseTemp + $amplitude * sin(($time->hour - 12) * pi() / 12);

                // Variation aléatoire pour l'humidité et le vent
                DonneeMeteo::create([
                    'installation_id' => $installation->id,
                    'date_heure' => $time,
                    'temperature' => $temperature,
                    'humidite' => rand(60, 70), // humidité entre 60% et 70%
                    'vitesse_vent' => rand(8, 12), // vent entre 8 et 12 km/h
                    'direction_vent' => rand(0, 360),
                    'irradiation' => rand(800, 1000), // irradiation entre 800 et 1000 W/m²
                    'ensoleillement' => rand(5, 8) // ensoleillement entre 5 et 8 heures
                ]);
            }
        }
    }
}
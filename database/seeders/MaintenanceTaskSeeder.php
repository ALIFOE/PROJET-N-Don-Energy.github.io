<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaintenanceTask;
use App\Models\Installation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MaintenanceTaskSeeder extends Seeder
{
    public function run()
    {
        // Créer un utilisateur admin si nécessaire
        $user = User::where('email', 'admin@test.com')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Admin Test',
                'email' => 'admin@test.com',
                'password' => bcrypt('password123')
            ]);
        }

        // Se connecter en tant qu'admin pour le logging
        Auth::login($user);

        // S'assurer qu'il y a au moins une installation
        $installation = Installation::first();
        if (!$installation) {
            $installation = Installation::create([
                'user_id' => $user->id,
                'nom' => 'Installation Test',
                'adresse' => '123 Rue Test',
                'ville' => 'Ville Test',
                'code_postal' => '12345',
                'pays' => 'Pays Test',
                'puissance_installee' => 5000,
                'statut' => 'en_cours'
            ]);
        }

        // Créer quelques tâches de maintenance
        $maintenances = [
            [
                'installation_id' => $installation->id,
                'user_id' => $user->id,
                'type' => 'preventive',
                'description' => 'Inspection des panneaux solaires',
                'date' => now()->addDays(7),
                'statut' => 'en_cours',
                'priorite' => 'moyenne'
            ],
            [
                'installation_id' => $installation->id,
                'user_id' => $user->id,
                'type' => 'corrective',
                'description' => 'Remplacement onduleur défectueux',
                'date' => now()->addDays(2),
                'statut' => 'en_cours',
                'priorite' => 'haute'
            ],
            [
                'installation_id' => $installation->id,
                'user_id' => $user->id,
                'type' => 'predictive',
                'description' => 'Analyse des performances',
                'date' => now()->addDays(14),
                'statut' => 'en_cours',
                'priorite' => 'basse'
            ]
        ];

        foreach ($maintenances as $maintenance) {
            MaintenanceTask::create($maintenance);
        }

        // Déconnecter l'utilisateur
        Auth::logout();
    }
}
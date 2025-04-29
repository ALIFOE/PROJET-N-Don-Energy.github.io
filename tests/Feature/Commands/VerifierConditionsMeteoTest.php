<?php

namespace Tests\Feature\Commands;

use App\Models\Installation;
use App\Models\DonneeMeteo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class VerifierConditionsMeteoTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_commande_verifie_les_conditions_meteo()
    {
        // Créer une installation avec configuration d'alertes
        $user = User::factory()->create();
        $installation = Installation::create([
            'user_id' => $user->id,
            'nom' => 'Test Installation',
            'alerte_meteo_config' => [
                'temperature_max' => 35,
                'temperature_min' => 0,
                'vent_max' => 50,
                'notifications_email' => true,
                'notifications_app' => true
            ]
        ]);

        // Créer des données météo dépassant les seuils
        DonneeMeteo::create([
            'installation_id' => $installation->id,
            'date_heure' => Carbon::now(),
            'temperature' => 36,
            'humidite' => 65,
            'vitesse_vent' => 55,
            'direction_vent' => 180,
            'irradiation' => 800,
            'ensoleillement' => 5
        ]);

        // Exécuter la commande
        $this->artisan('meteo:verifier-conditions')
            ->expectsOutput('2 alerte(s) météo générée(s)')
            ->assertExitCode(0);

        // Vérifier que les notifications ont été créées
        $this->assertDatabaseCount('notifications', 2);
    }
}
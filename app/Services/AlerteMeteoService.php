<?php

namespace App\Services;

use App\Models\Installation;
use App\Models\DonneeMeteo;
use App\Notifications\AlerteMeteo;
use Carbon\Carbon;

class AlerteMeteoService
{
    public function verifierConditionsMeteo(Installation $installation)
    {
        $config = $installation->alerte_meteo_config;
        if (!$config) {
            return;
        }

        $dernieresDonnees = DonneeMeteo::where('installation_id', $installation->id)
            ->latest('date_heure')
            ->first();

        if (!$dernieresDonnees) {
            return;
        }

        $alertes = [];

        // Vérifier la température
        if ($dernieresDonnees->temperature > $config['temperature_max']) {
            $alertes[] = [
                'message' => "Température élevée ({$dernieresDonnees->temperature}°C) - Risque de baisse de rendement",
                'type' => 'danger'
            ];
        }

        if ($dernieresDonnees->temperature < $config['temperature_min']) {
            $alertes[] = [
                'message' => "Température basse ({$dernieresDonnees->temperature}°C) - Risque de gel",
                'type' => 'danger'
            ];
        }

        // Vérifier le vent
        if ($dernieresDonnees->vitesse_vent > $config['vent_max']) {
            $alertes[] = [
                'message' => "Vent fort ({$dernieresDonnees->vitesse_vent} km/h) - Risque pour l'installation",
                'type' => 'danger'
            ];
        }

        // Envoyer les notifications
        foreach ($alertes as $alerte) {
            $installation->notify(new AlerteMeteo($alerte['message'], $alerte['type']));
        }

        return $alertes;
    }
}
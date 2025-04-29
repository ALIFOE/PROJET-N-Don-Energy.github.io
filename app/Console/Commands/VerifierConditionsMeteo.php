<?php

namespace App\Console\Commands;

use App\Models\Installation;
use App\Services\AlerteMeteoService;
use Illuminate\Console\Command;

class VerifierConditionsMeteo extends Command
{
    protected $signature = 'meteo:verifier-conditions';
    protected $description = 'Vérifie les conditions météo pour toutes les installations et envoie des alertes si nécessaire';

    protected $alerteMeteoService;

    public function __construct(AlerteMeteoService $alerteMeteoService)
    {
        parent::__construct();
        $this->alerteMeteoService = $alerteMeteoService;
    }

    public function handle()
    {
        $installations = Installation::whereNotNull('alerte_meteo_config')->get();
        $compteur = 0;

        foreach ($installations as $installation) {
            $alertes = $this->alerteMeteoService->verifierConditionsMeteo($installation);
            if ($alertes && count($alertes) > 0) {
                $compteur += count($alertes);
            }
        }

        $this->info("{$compteur} alerte(s) météo générée(s)");
        return 0;
    }
}
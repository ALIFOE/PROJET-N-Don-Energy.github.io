<?php

namespace App\Console\Commands;

use App\Models\Onduleur;
use App\Models\InverterData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\InverterService;

class FetchInverterData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inverters:fetch-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Récupère les données de performance de tous les onduleurs connectés';

    protected $inverterService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(InverterService $inverterService)
    {
        parent::__construct();
        $this->inverterService = $inverterService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $onduleurs = Onduleur::with('installation')->where('est_connecte', true)->get();

        $this->info('Début de la récupération des données pour ' . $onduleurs->count() . ' onduleur(s)');

        foreach ($onduleurs as $onduleur) {
            try {
                if (!$onduleur->installation) {
                    $this->warn("Pas d'installation trouvée pour l'onduleur {$onduleur->modele}");
                    continue;
                }

                $data = $this->inverterService->getLiveData($onduleur->installation);
                
                if ($data) {
                    InverterData::create([
                        'inverter_id' => $onduleur->id,
                        'power' => $data->current_power,
                        'daily_energy' => $data->daily_energy,
                        'total_energy' => $data->total_energy,
                        'temperature' => $data->temperature,
                        'efficiency' => $data->efficiency,
                        'status' => 'connected'
                    ]);
                    
                    $this->info("Données récupérées avec succès pour l'onduleur {$onduleur->modele} ({$onduleur->numero_serie})");
                } else {
                    Log::warning("Aucune donnée reçue pour l'onduleur ID {$onduleur->id}");
                    $this->warn("Aucune donnée reçue pour l'onduleur {$onduleur->modele}");
                }
            } catch (\Exception $e) {
                Log::error("Erreur lors de la récupération des données pour l'onduleur ID {$onduleur->id}: " . $e->getMessage());
                $this->error("Erreur pour l'onduleur {$onduleur->modele}: " . $e->getMessage());
            }
        }

        $this->info('Récupération des données terminée');
        return Command::SUCCESS;
    }
}

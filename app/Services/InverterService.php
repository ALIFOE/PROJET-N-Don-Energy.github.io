<?php

namespace App\Services;

use App\Factories\InverterConnectorFactory;
use Illuminate\Support\Facades\Log;

class InverterService
{
    protected $connector;

    public function __construct(InverterConnectorFactory $factory)
    {
        $this->connector = $factory->createConnector();
    }

    public function getCurrentData()
    {
        try {
            $data = $this->connector->fetchRealTimeData();

            return [
                'production_actuelle' => $data['current_power'] ?? 0,
                'production_journaliere' => $data['daily_energy'] ?? 0,
                'etat' => $this->determinerEtatOnduleur($data['status'] ?? null),
                'derniere_mise_a_jour' => now()->format('Y-m-d H:i:s'),
            ];
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération des données de l\'onduleur: ' . $e->getMessage());
            
            return [
                'production_actuelle' => 0,
                'production_journaliere' => 0,
                'etat' => 'Erreur de connexion',
                'derniere_mise_a_jour' => now()->format('Y-m-d H:i:s'),
            ];
        }
    }

    private function determinerEtatOnduleur($status)
    {
        if ($status === null) {
            return 'Indéterminé';
        }

        $etats = [
            0 => 'Normal',
            1 => 'Avertissement',
            2 => 'Erreur',
            3 => 'Hors ligne'
        ];

        return $etats[$status] ?? 'Indéterminé';
    }
}
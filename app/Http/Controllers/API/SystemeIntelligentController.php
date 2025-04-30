<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Alerte;
use App\Models\Installation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SystemeIntelligentController extends Controller
{
    public function getAlertes()
    {
        $installations = Installation::where('user_id', auth()->id())->pluck('id');
        
        $alertes = Alerte::whereIn('installation_id', $installations)
            ->where('date_resolution', null)
            ->orderBy('niveau', 'desc')
            ->orderBy('date_creation', 'desc')
            ->get()
            ->map(function ($alerte) {
                return [
                    'type' => $alerte->type,
                    'description' => $alerte->description,
                    'niveau' => $this->getNiveauClass($alerte->niveau),
                    'date' => Carbon::parse($alerte->date_creation)->format('d/m/Y H:i')
                ];
            });

        return response()->json($alertes);
    }

    public function getDiagnostic()
    {
        $installations = Installation::where('user_id', auth()->id())->get();
        
        $diagnostics = [];
        $totalPerformance = 0;
        $systemStatus = 'optimal';

        foreach ($installations as $installation) {
            $performance = $this->calculatePerformance($installation);
            $status = $this->getSystemStatus($installation);
            
            $totalPerformance += $performance;
            if ($status !== 'optimal') {
                $systemStatus = 'attention';
            }

            $diagnostics[] = [
                'message' => "Performance de l'installation {$installation->nom}: {$performance}%",
                'status' => $performance > 80 ? 'success' : 'warning'
            ];

            // Vérification des onduleurs
            foreach ($installation->onduleurs as $onduleur) {
                $diagnostics[] = [
                    'message' => "État de l'onduleur {$onduleur->modele}: " . ($onduleur->est_connecte ? 'Connecté' : 'Déconnecté'),
                    'status' => $onduleur->est_connecte ? 'success' : 'error'
                ];
            }
        }

        $averagePerformance = $installations->count() > 0 ? 
            round($totalPerformance / $installations->count()) : 0;

        return response()->json([
            'performance' => $averagePerformance,
            'status' => $systemStatus,
            'details' => $diagnostics
        ]);
    }

    public function getNextMaintenance()
    {
        $nextMaintenance = \App\Models\MaintenanceTask::whereHas('installation', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->where('date', '>=', now())
            ->where('statut', 'planifiee')
            ->orderBy('date')
            ->first();

        if ($nextMaintenance) {
            return response()->json([
                'type' => ucfirst($nextMaintenance->type),
                'date' => $nextMaintenance->date->format('d/m/Y'),
                'description' => $nextMaintenance->description
            ]);
        }

        return response()->json(null);
    }

    public function getRecommendations()
    {
        $installations = Installation::where('user_id', auth()->id())->get();
        $recommendations = [];

        foreach ($installations as $installation) {
            // Vérification de la performance
            $performance = $this->calculatePerformance($installation);
            if ($performance < 80) {
                $recommendations[] = [
                    'title' => 'Optimisation de la performance',
                    'description' => "La performance de l'installation {$installation->nom} est sous-optimale. Une maintenance pourrait améliorer les performances.",
                    'action' => [
                        'label' => 'Planifier une maintenance',
                        'url' => route('maintenance-predictive')
                    ]
                ];
            }

            // Vérification de l'âge des équipements
            if ($installation->created_at->diffInYears(now()) >= 5) {
                $recommendations[] = [
                    'title' => 'Maintenance préventive recommandée',
                    'description' => "L'installation {$installation->nom} a plus de 5 ans. Une maintenance préventive est recommandée.",
                    'action' => [
                        'label' => 'Voir les détails',
                        'url' => route('maintenance-predictive')
                    ]
                ];
            }
        }

        return response()->json($recommendations);
    }

    private function calculatePerformance(Installation $installation)
    {
        // Logique de calcul de performance basée sur les données de production
        // À personnaliser selon vos besoins
        return rand(70, 100); // Simulation pour l'exemple
    }

    private function getSystemStatus(Installation $installation)
    {
        // Logique de détermination du statut
        return $installation->alertes()->where('niveau', 'danger')->exists() ? 'attention' : 'optimal';
    }

    private function getNiveauClass($niveau)
    {
        return match($niveau) {
            'danger' => 'red',
            'warning' => 'yellow',
            'info' => 'blue',
            default => 'gray'
        };
    }
}

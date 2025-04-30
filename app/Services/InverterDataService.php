<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Onduleur;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class InverterDataService
{
    protected $onduleur;

    public function __construct(Onduleur $onduleur)
    {
        $this->onduleur = $onduleur;
    }

    public function getCurrentData()
    {
        return Cache::remember('inverter_current_data', 60, function () {
            $data = $this->onduleur->fetchCurrentData();
            
            return [
                'production_actuelle' => $data['current_power'] ?? 0,
                'temperature' => $data['temperature'] ?? 25,
                'irradiance' => $data['irradiance'] ?? 800,
                'battery_level' => $data['battery_level'] ?? 85,
                'system_status' => $this->getSystemStatus($data),
                'efficiency' => $this->calculateEfficiency($data),
            ];
        });
    }

    public function getProductionData($period = '24h')
    {
        $endDate = Carbon::now();
        $startDate = $this->getStartDate($period);

        return Cache::remember("inverter_production_${period}", 300, function () use ($startDate, $endDate) {
            $data = $this->onduleur->fetchProductionData($startDate, $endDate);
            
            return [
                'production' => $data['production'],
                'temperature' => $data['temperature'],
                'irradiance' => $data['irradiance'],
                'stats' => [
                    'production_moyenne' => array_sum($data['production']) / count($data['production']),
                    'production_totale' => array_sum($data['production']),
                    'rendement' => $this->calculateAverageEfficiency($data),
                ]
            ];
        });
    }

    public function getPerformanceData()
    {
        return Cache::remember('inverter_performance', 3600, function () {
            $data = $this->onduleur->fetchPerformanceData();
            
            return [
                'score' => $this->calculatePerformanceScore($data),
                'points_forts' => $this->analyzeStrengths($data),
                'points_amelioration' => $this->analyzeImprovements($data),
            ];
        });
    }

    protected function getSystemStatus($data)
    {
        if ($data['error_code'] ?? false) {
            return ['status' => 'error', 'message' => 'Erreur système'];
        }
        if ($data['warning_code'] ?? false) {
            return ['status' => 'warning', 'message' => 'Attention requise'];
        }
        return ['status' => 'optimal', 'message' => 'Fonctionnement optimal'];
    }

    protected function calculateEfficiency($data)
    {
        try {
            $irradiance = $data['irradiance'] ?? null;
            $surface = $this->onduleur->surface ?? null;
            $currentPower = $data['current_power'] ?? null;

            // Log des valeurs pour le débogage
            Log::debug('Calcul efficacité - Valeurs:', [
                'irradiance' => $irradiance,
                'surface' => $surface,
                'current_power' => $currentPower
            ]);

            // Vérification détaillée des valeurs
            if (!is_numeric($irradiance) || $irradiance <= 0) {
                Log::warning('Irradiance invalide pour le calcul d\'efficacité');
                return 0;
            }

            if (!is_numeric($surface) || $surface <= 0) {
                Log::warning('Surface invalide pour le calcul d\'efficacité');
                return 0;
            }

            if (!is_numeric($currentPower)) {
                Log::warning('Puissance actuelle invalide pour le calcul d\'efficacité');
                return 0;
            }

            $denominator = $irradiance * $surface;
            
            // Vérification finale avant division
            if ($denominator === 0) {
                Log::warning('Dénominateur nul détecté dans le calcul d\'efficacité');
                return 0;
            }

            $efficiency = ($currentPower / $denominator) * 100;
            
            // Validation du résultat
            if (!is_finite($efficiency)) {
                Log::warning('Résultat non valide dans le calcul d\'efficacité');
                return 0;
            }

            return $efficiency;
        } catch (\Exception $e) {
            Log::error('Erreur dans le calcul d\'efficacité: ' . $e->getMessage());
            return 0;
        }
    }

    protected function calculateAverageEfficiency($data)
    {
        try {
            $efficiencies = [];
            foreach ($data['production'] as $index => $production) {
                // Vérification que toutes les valeurs nécessaires sont présentes et valides
                if (!isset($data['irradiance'][$index]) || 
                    !is_numeric($data['irradiance'][$index]) || 
                    !is_numeric($production) ||
                    !is_numeric($this->onduleur->surface)) {
                    continue;
                }

                $denominator = $data['irradiance'][$index] * $this->onduleur->surface;
                
                // Vérification que le dénominateur est suffisamment grand
                if ($denominator > 0.001) {
                    $efficiency = ($production / $denominator) * 100;
                    // Vérification que le résultat est dans une plage raisonnable
                    if (is_finite($efficiency) && $efficiency >= 0 && $efficiency <= 100) {
                        $efficiencies[] = $efficiency;
                    }
                }
            }

            // Vérification qu'il y a au moins une valeur valide
            return !empty($efficiencies) ? array_sum($efficiencies) / count($efficiencies) : 0;
        } catch (\Exception $e) {
            Log::error('Erreur dans le calcul de l\'efficacité moyenne: ' . $e->getMessage());
            return 0;
        }
    }

    protected function getStartDate($period)
    {
        return match($period) {
            '24h' => Carbon::now()->subDay(),
            'semaine' => Carbon::now()->subWeek(),
            'mois' => Carbon::now()->subMonth(),
            'annee' => Carbon::now()->subYear(),
            default => Carbon::now()->subDay(),
        };
    }

    protected function calculatePerformanceScore($data)
    {
        try {
            $efficiency = $this->calculateEfficiency($data);
            if (!is_numeric($efficiency)) {
                $efficiency = 0;
            }

            $criteria = [
                'efficiency' => min(max($efficiency, 0), 100) * 0.4,
                'uptime' => min(max(($data['uptime'] ?? 95), 0), 100) * 0.2,
                'maintenance' => min(max(($data['maintenance_score'] ?? 90), 0), 100) * 0.2,
                'cleanliness' => min(max(($data['cleanliness_score'] ?? 85), 0), 100) * 0.2
            ];

            $score = array_sum($criteria);
            return is_finite($score) ? $score : 0;
        } catch (\Exception $e) {
            Log::error('Erreur dans le calcul du score de performance: ' . $e->getMessage());
            return 0;
        }
    }

    protected function analyzeStrengths($data)
    {
        $strengths = [];
        
        if (($data['efficiency'] ?? 0) > 85) {
            $strengths[] = 'Rendement optimal des panneaux';
        }
        if (($data['maintenance_score'] ?? 0) > 90) {
            $strengths[] = 'Maintenance régulière';
        }
        if (($data['uptime'] ?? 0) > 95) {
            $strengths[] = 'Excellente disponibilité';
        }

        return $strengths;
    }

    protected function analyzeImprovements($data)
    {
        $improvements = [];
        
        if (($data['cleanliness_score'] ?? 100) < 90) {
            $improvements[] = 'Nettoyage des panneaux recommandé';
        }
        if (($data['angle_optimization'] ?? 100) < 95) {
            $improvements[] = 'Optimisation de l\'angle possible';
        }
        if (($data['inverter_efficiency'] ?? 100) < 90) {
            $improvements[] = 'Maintenance de l\'onduleur conseillée';
        }

        return $improvements;
    }
}
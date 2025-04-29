<?php

namespace App\Http\Controllers;

use App\Models\DonneeMeteo;
use App\Models\Installation;
use App\Services\InverterService;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    protected $inverterService;

    public function __construct(InverterService $inverterService)
    {
        $this->inverterService = $inverterService;
    }

    public function getPerformanceData()
    {
        // Données de l'onduleur en temps réel
        $inverterData = $this->inverterService->getCurrentData();
        
        // Données régionales
        $regionalData = $this->getRegionalPerformance();

        return response()->json([
            'inverter' => $inverterData,
            'regional' => $regionalData
        ]);
    }

    private function getRegionalPerformance()
    {
        // Récupérer les données météo actuelles de la région
        $currentMeteoData = DonneeMeteo::latest()->first();
        
        // Calculer la production totale de la région
        $totalRegionalProduction = Installation::where('region', session('region'))
            ->sum('production_actuelle');

        return [
            'production_totale' => $totalRegionalProduction,
            'irradiation' => $currentMeteoData->irradiation ?? 0,
            'performance_collective' => $this->calculateRegionalPerformance()
        ];
    }

    private function calculateRegionalPerformance()
    {
        // Logique pour calculer la performance moyenne de la région
        $installations = Installation::where('region', session('region'))->get();
        $totalEfficiency = 0;
        $count = 0;

        foreach ($installations as $installation) {
            if ($installation->efficacite > 0) {
                $totalEfficiency += $installation->efficacite;
                $count++;
            }
        }

        return $count > 0 ? ($totalEfficiency / $count) : 0;
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Installation;
use App\Models\ProductionData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class SuiviProductionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function generateSimulatedData($startDate, $endDate)
    {
        $data = [];
        $current = $startDate->copy();

        while ($current <= $endDate) {
            // Simuler une courbe de production basée sur l'heure de la journée
            $hour = $current->hour;
            
            // Production nulle la nuit (de 20h à 6h)
            if ($hour < 6 || $hour >= 20) {
                $power = 0;
            } else {
                // Courbe en cloche avec pic à 13h
                $peakHour = 13;
                $maxPower = 5.0; // 5 kW max
                $hourDiff = abs($hour - $peakHour);
                $power = $maxPower * (1 - pow($hourDiff / 7, 2));
                
                // Ajouter un peu de bruit aléatoire (±10%)
                $power *= (1 + (mt_rand(-100, 100) / 1000));
                $power = max(0, $power); // S'assurer que la puissance n'est pas négative
            }

            // Simuler l'ensoleillement
            $irradiance = $power * 200; // Relation approximative avec la puissance

            // Simuler la température
            $baseTemp = 20; // Température de base
            $tempVariation = 5; // Variation de température
            $temperature = $baseTemp + $tempVariation * sin(($hour - 6) * pi() / 12) + (mt_rand(-20, 20) / 10);

            $data[$current->format('Y-m-d H:i:s')] = [
                'power' => round($power, 2),
                'irradiance' => round($irradiance),
                'temperature' => round($temperature, 1),
            ];

            $current->addHour();
        }

        return $data;
    }

    public function index()
    {
        return view('suivi-production');
    }

    public function getData()
    {
        $user = Auth::user();
        $installation = Installation::where('user_id', $user->id)->first();

        if (!$installation) {
            return response()->json([
                'error' => 'Aucune installation trouvée'
            ], 404);
        }

        // Définir la période pour les données (dernières 24 heures)
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subHours(24);

        // Récupérer les vraies données si elles existent
        $realData = ProductionData::where('installation_id', $installation->id)
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->orderBy('timestamp')
            ->get();

        if ($realData->isEmpty()) {
            // Générer des données simulées si aucune donnée réelle n'existe
            $simulatedData = $this->generateSimulatedData($startDate, $endDate);
            
            $production = [];
            $irradiance = [];
            $temperature = [];
            $batteryLevels = [];

            foreach ($simulatedData as $timestamp => $data) {
                $timeKey = Carbon::parse($timestamp)->format('H:i');
                $production[$timeKey] = $data['power'];
                $irradiance[$timeKey] = $data['irradiance'];
                $temperature[$timeKey] = $data['temperature'];
                
                // Simuler le niveau de batterie (décroissance progressive avec recharge pendant la journée)
                $hour = Carbon::parse($timestamp)->hour;
                if ($hour >= 6 && $hour < 20) {
                    $batteryLevels[$timeKey] = min(95, 60 + ($data['power'] * 7)); // Recharge pendant la journée
                } else {
                    $batteryLevels[$timeKey] = max(20, 80 - (abs(20 - $hour) * 2)); // Décharge la nuit
                }
            }
        } else {
            // Utiliser les vraies données
            $production = [];
            $irradiance = [];
            $temperature = [];
            $batteryLevels = [];

            foreach ($realData as $data) {
                $timeKey = $data->timestamp->format('H:i');
                $production[$timeKey] = $data->current_power;
                $irradiance[$timeKey] = $data->irradiance;
                $temperature[$timeKey] = $data->temperature;
                // Note: le niveau de batterie devrait venir d'une autre table dans un cas réel
                $batteryLevels[$timeKey] = rand(20, 95); // Simulation pour demo
            }
        }

        return response()->json([
            'production' => $production,
            'irradiance' => $irradiance,
            'temperature' => $temperature,
            'batteryLevels' => $batteryLevels
        ]);
    }

    public function exportPDF()
    {
        $user = Auth::user();
        $installation = Installation::where('user_id', $user->id)->first();

        if (!$installation) {
            return back()->with('error', 'Aucune installation trouvée');
        }

        // Définir la période pour les données (dernières 24 heures)
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subHours(24);

        // Récupérer les données
        $realData = ProductionData::where('installation_id', $installation->id)
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->orderBy('timestamp')
            ->get();

        if ($realData->isEmpty()) {
            $simulatedData = $this->generateSimulatedData($startDate, $endDate);
            $data = collect($simulatedData)->map(function ($item, $timestamp) {
                return [
                    'timestamp' => $timestamp,
                    'power' => $item['power'],
                    'irradiance' => $item['irradiance'],
                    'temperature' => $item['temperature']
                ];
            });
        } else {
            $data = $realData;
        }

        $pdf = Pdf::loadView('pdf.suivi-production', [
            'data' => $data,
            'installation' => $installation,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);

        return $pdf->download('suivi-production-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportCSV()
    {
        $user = Auth::user();
        $installation = Installation::where('user_id', $user->id)->first();

        if (!$installation) {
            return back()->with('error', 'Aucune installation trouvée');
        }

        // Définir la période pour les données (dernières 24 heures)
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subHours(24);

        // Récupérer les données
        $realData = ProductionData::where('installation_id', $installation->id)
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->orderBy('timestamp')
            ->get();

        if ($realData->isEmpty()) {
            $simulatedData = $this->generateSimulatedData($startDate, $endDate);
            $data = collect($simulatedData)->map(function ($item, $timestamp) {
                return [
                    'timestamp' => $timestamp,
                    'puissance' => $item['power'],
                    'irradiance' => $item['irradiance'],
                    'temperature' => $item['temperature']
                ];
            });
        } else {
            $data = $realData->map(function ($item) {
                return [
                    'timestamp' => $item->timestamp,
                    'puissance' => $item->current_power,
                    'irradiance' => $item->irradiance,
                    'temperature' => $item->temperature
                ];
            });
        }

        // Générer le nom du fichier CSV
        $filename = 'suivi-production-' . now()->format('Y-m-d') . '.csv';

        // Créer la réponse CSV
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Ajouter l'en-tête UTF-8 BOM pour la compatibilité Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // En-têtes des colonnes
            fputcsv($file, ['Date et Heure', 'Puissance (kW)', 'Irradiance (W/m²)', 'Température (°C)']);

            // Données
            foreach ($data as $row) {
                fputcsv($file, [
                    Carbon::parse($row['timestamp'])->format('d/m/Y H:i:s'),
                    number_format($row['puissance'], 2),
                    round($row['irradiance']),
                    number_format($row['temperature'], 1)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

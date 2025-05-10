<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RealtimeDataController extends Controller
{
    public function production()
    {
        // Exemple de données simulées (à remplacer par des données réelles de la base ou d'un service)
        $data = [
            'labels' => [
                '00:00', '02:00', '04:00', '06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00'
            ],
            'production' => [0, 0, 10, 50, 120, 200, 250, 220, 150, 60, 20, 0],
            'consommation' => [20, 18, 15, 30, 60, 100, 180, 200, 170, 100, 50, 30],
        ];
        return response()->json($data);
    }
}

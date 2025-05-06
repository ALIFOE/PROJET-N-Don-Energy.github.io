<?php

namespace App\Http\Controllers\Technicien;

use App\Http\Controllers\Controller;
use App\Models\Installation;
use App\Models\Maintenance;
use App\Models\Alerte;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:technician');
    }

    public function index()
    {
        // Récupération des statistiques
        $installations_en_cours = Installation::where('statut', 'en_cours')->count();
        $maintenances_prevues = Maintenance::where('date_prevue', '>=', Carbon::now())
            ->where('statut', '!=', 'terminee')
            ->count();
        $interventions_urgentes = Maintenance::where('priorite', 'haute')
            ->where('statut', '!=', 'terminee')
            ->count();

        // Récupération des prochaines interventions
        $prochaines_interventions = collect()
            ->merge(
                Installation::where('statut', '!=', 'terminee')
                    ->where('date_installation', '>=', Carbon::now())
                    ->take(5)
                    ->get()
                    ->map(function ($item) {
                        $item->type = 'installation';
                        return $item;
                    })
            )
            ->merge(
                Maintenance::where('statut', '!=', 'terminee')
                    ->where('date_prevue', '>=', Carbon::now())
                    ->take(5)
                    ->get()
                    ->map(function ($item) {
                        $item->type = 'maintenance';
                        return $item;
                    })
            )
            ->sortBy('date_prevue')
            ->take(5);

        // Récupération des alertes récentes
        $alertes = Alerte::where('niveau', '!=', 'info')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('technician.dashboard', compact(
            'installations_en_cours',
            'maintenances_prevues',
            'interventions_urgentes',
            'prochaines_interventions',
            'alertes'
        ));
    }
}

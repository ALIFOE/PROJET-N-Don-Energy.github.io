<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Dimensionnement;
use App\Models\LogActivite;
use App\Models\Utilisateur;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Contrôleur pour gérer le tableau de bord client
 */
class DashboardController extends Controller
{
    /**
     * Constructeur du contrôleur
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affiche le tableau de bord de l'utilisateur
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        // Récupérer les dimensionnements récents
        $dimensionnements = $user->dimensionnements()
            ->latest()
            ->take(5)
            ->get();

        // Récupérer les onduleurs connectés
        $onduleurs = $user->onduleurs()
            ->where('est_connecte', true)
            ->get();

        // Récupérer les données de performances
        $performanceData = [];
        foreach ($onduleurs as $onduleur) {
            $donneeRecente = $onduleur->donneesProduction()
                ->latest('date_heure')
                ->first();

            $performanceData[$onduleur->id] = [
                'production_actuelle' => $donneeRecente ? $donneeRecente->puissance_instantanee : 0,
                'production_journaliere' => $onduleur->donneesProduction()
                    ->whereDate('date_heure', today())
                    ->sum('energie_produite'),
                'rendement' => $donneeRecente ? ($donneeRecente->puissance_instantanee / $onduleur->puissance_nominale * 100) : 0,
            ];
        }

        // Filtrage des activités
        $activitesQuery = LogActivite::where('user_id', $user->id);

        if ($request->has('action')) {
            $activitesQuery->where('action', $request->action);
        }

        if ($request->has('date')) {
            switch ($request->date) {
                case 'aujourd\'hui':
                    $activitesQuery->whereDate('created_at', today());
                    break;
                case 'semaine':
                    $activitesQuery->where('created_at', '>=', now()->startOfWeek());
                    break;
                case 'mois':
                    $activitesQuery->where('created_at', '>=', now()->startOfMonth());
                    break;
            }
        }

        $activites = $activitesQuery->latest()->paginate(10);

        // Récupérer les notifications non lues
        $notifications = $user->notifications()
            ->whereNull('read_at')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'dimensionnements',
            'onduleurs',
            'performanceData',
            'activites',
            'notifications'
        ));
    }
}

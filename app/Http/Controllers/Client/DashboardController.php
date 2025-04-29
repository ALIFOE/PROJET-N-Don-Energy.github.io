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
        $dimensionnements = Dimensionnement::where('user_id', auth()->id())
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        $activitesQuery = LogActivite::where('user_id', auth()->id());

        // Filtrage par type d'action
        if ($request->has('action')) {
            $activitesQuery->where('action', $request->action);
        }

        // Filtrage par date
        if ($request->has('date')) {
            switch ($request->date) {
                case 'aujourd\'hui':
                    $activitesQuery->whereDate('created_at', today());
                    break;
                case 'semaine':
                    $activitesQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'mois':
                    $activitesQuery->whereMonth('created_at', now()->month);
                    break;
            }
        }

        // Exclure les actions de type "connexion"
        $activitesQuery->where('action', '!=', 'connexion');

        $activites = $activitesQuery->latest()->paginate(10)->withQueryString();

        return view('dashboard', compact('dimensionnements', 'activites'));
    }
}

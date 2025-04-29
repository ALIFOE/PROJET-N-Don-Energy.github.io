<?php

namespace App\Http\Controllers;

use App\Models\LogActivite;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class LogActiviteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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

        $activites = $activitesQuery->latest()->paginate(10);

        return view('activites.index', compact('activites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LogActivite  $logActivite
     * @return \Illuminate\Http\Response
     */
    public function show(LogActivite $logActivite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LogActivite  $logActivite
     * @return \Illuminate\Http\Response
     */
    public function edit(LogActivite $logActivite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LogActivite  $logActivite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LogActivite $logActivite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LogActivite  $logActivite
     * @return \Illuminate\Http\Response
     */
    public function destroy(LogActivite $logActivite)
    {
        //
    }

    /**
     * Export activities to PDF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportPDF(Request $request)
    {
        $activitesQuery = LogActivite::where('user_id', auth()->id());

        // Appliquer les mêmes filtres que pour l'affichage
        if ($request->has('action')) {
            $activitesQuery->where('action', $request->action);
        }

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

        $activites = $activitesQuery->latest()->get();

        $pdf = PDF::loadView('activites.pdf', compact('activites'));

        return $pdf->download('mes-activites-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Supprime toutes les activités.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset()
    {
        LogActivite::truncate(); // Supprime toutes les entrées de la table

        return redirect()->route('activites.index')->with('success', 'Tous les historiques ont été supprimés avec succès.');
    }
}

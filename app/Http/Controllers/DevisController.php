<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use App\Models\User;
use App\Services\DevisAnalyzer;
use App\Notifications\NewDevisNotification;
use App\Events\ClientActivity;
use App\Mail\DevisConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DevisController extends Controller
{
    private $devisAnalyzer;

    public function __construct(DevisAnalyzer $devisAnalyzer)
    {
        $this->devisAnalyzer = $devisAnalyzer;
    }

    public function create()
    {
        return view('devis.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string|max:255',
            'type_batiment' => 'required|string',
            'facture_mensuelle' => 'nullable|numeric',
            'consommation_annuelle' => 'required|numeric',
            'type_toiture' => 'required|string',
            'orientation' => 'required|string',
            'objectifs' => 'required|array',
            'message' => 'nullable|string'
        ]);

        // Analyse du devis
        $analyse = $this->devisAnalyzer->analyserDevis($validated);

        // Sauvegarder le devis avec l'analyse
        $devisData = array_merge($validated, ['analyse_technique' => json_encode($analyse)]);
        $devis = Devis::create($devisData);

        // Envoyer le mail de confirmation au client
        Mail::to($validated['email'])->send(new DevisConfirmationMail($devis));

        // Envoyer une notification à tous les administrateurs
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewDevisNotification($devis));
        }

        // Déclencher l'événement pour la notification des administrateurs
        event(new ClientActivity('devis_request', [
            'name' => $validated['nom'] . ' ' . $validated['prenom'],
            'email' => $validated['email'],
            'id' => $devis->id,
            'type_batiment' => $validated['type_batiment']
        ]));

        // Redirection vers la page de résultats
        return redirect()->route('devis.resultats', $devis)
            ->with('success', 'Votre demande de devis a été analysée avec succès.');
    }

    public function resultats(Devis $devis)
    {
        return view('devis.resultats', [
            'devis' => $devis,
            'analyse' => $devis->analyse_technique ?? []
        ]);
    }

    public function downloadPdf(Devis $devis)
    {
        $analyseData = is_string($devis->analyse_technique) 
            ? json_decode($devis->analyse_technique, true) 
            : $devis->analyse_technique;

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('devis.pdf', [
            'devis' => $devis,
            'analyseData' => $analyseData
        ]);

        return $pdf->download('resultats-analyse-' . $devis->id . '.pdf');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Devis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DevisController extends Controller
{    public function index()
    {
        $devis = Devis::latest()->paginate(10);
        return view('admin.devis.index', compact('devis'));
    }

    public function show($id)
    {
        $devis = Devis::findOrFail($id);
        // Sécurisation du champ analyse_technique
        if (is_string($devis->analyse_technique) && !empty($devis->analyse_technique)) {
            $decoded = json_decode($devis->analyse_technique, true);
            $devis->analyse_technique = is_array($decoded) ? $decoded : [];
        } elseif (is_null($devis->analyse_technique) || !is_array($devis->analyse_technique)) {
            $devis->analyse_technique = [];
        }

        // Sécurisation du champ objectifs
        if (is_string($devis->objectifs) && !empty($devis->objectifs)) {
            $decoded = json_decode($devis->objectifs, true);
            $devis->objectifs = is_array($decoded) ? $decoded : [];
        } elseif (is_null($devis->objectifs) || !is_array($devis->objectifs)) {
            $devis->objectifs = [];
        }

        // Valeurs par défaut pour les champs attendus
        $devis->nom = $devis->nom ?? '';
        $devis->prenom = $devis->prenom ?? '';
        $devis->email = $devis->email ?? '';
        $devis->telephone = $devis->telephone ?? '';
        $devis->adresse = $devis->adresse ?? '';
        $devis->type_batiment = $devis->type_batiment ?? '';
        $devis->facture_mensuelle = $devis->facture_mensuelle ?? 0;
        $devis->consommation_annuelle = $devis->consommation_annuelle ?? 0;
        $devis->type_toiture = $devis->type_toiture ?? '';
        $devis->orientation = $devis->orientation ?? '';
        $devis->message = $devis->message ?? '';
        $devis->statut = $devis->statut ?? 'en_attente';

        // Log pour le débogage
        Log::info('Devis details:', ['devis' => $devis->toArray()]);

        return view('admin.devis.show', compact('devis'));
    }

    public function destroy(Devis $devi)
    {
        $devi->delete();
        return redirect()->route('admin.devis.index')
            ->with('success', 'Le devis a été supprimé avec succès.');
    }public function downloadPdf($id)
    {
        $devis = Devis::findOrFail($id);
        
        // Decode analyse_technique if it's stored as a JSON string
        if (is_string($devis->analyse_technique)) {
            $devis->analyse_technique = json_decode($devis->analyse_technique, true);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.devis', [
            'devis' => $devis,
            'title' => 'Résultats Analyse Projet Solaire - Devis N°' . $id
        ]);
        
        return $pdf->download('analyse-projet-solaire-devis-' . $devis->id . '.pdf');
    }

    public function updateStatus(Request $request, $id)
    {
        $devis = Devis::findOrFail($id);
        $validatedData = $request->validate([
            'status' => 'required|string|in:en_attente,en_cours,accepte,refuse'
        ]);

        $devis->update(['statut' => $validatedData['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour avec succès',
            'status_label' => $devis->status_label,
            'status_color' => $devis->status_color
        ]);
    }
}
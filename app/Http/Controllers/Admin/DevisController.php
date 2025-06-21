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

    public function show(Devis $devis)
    {
        // Décoder l'analyse technique si elle est stockée en JSON
        if (is_string($devis->analyse_technique)) {
            $devis->analyse_technique = json_decode($devis->analyse_technique, true);
        }

        // Décoder les objectifs s'ils sont stockés en JSON
        if (is_string($devis->objectifs)) {
            $devis->objectifs = json_decode($devis->objectifs, true);
        }

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

    public function updateStatus(Request $request, Devis $devis)
    {
        $validatedData = $request->validate([
            'status' => 'required|string|in:en_attente,en_cours,accepte,refuse'
        ]);

        $devis->update(['statut' => $validatedData['status']]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès',
                'status_label' => $devis->status_label,
                'status_color' => $devis->status_color
            ]);
        }

        return back()->with('success', 'Statut mis à jour avec succès');
    }
}
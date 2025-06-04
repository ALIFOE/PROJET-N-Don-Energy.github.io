<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Devis;
use App\Traits\NotificationMarkable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DevisController extends Controller
{    
    use NotificationMarkable;

    public function index()
    {
        $this->markNotificationsAsRead('App\Notifications\NewDevisNotification');
        $devis = Devis::latest()->paginate(10);
        return view('admin.devis.index', compact('devis'));
    }

    public function show(Devis $devis)
    {
        $this->markNotificationsAsRead('App\Notifications\NewDevisNotification');
        // Décoder l'analyse technique si elle est stockée en JSON
        if (is_string($devis->analyse_technique)) {
            $devis->analyse_technique = json_decode($devis->analyse_technique, true);
        }

        // Décoder les objectifs s'ils sont stockés en JSON
        if (is_string($devis->objectifs)) {
            $devis->objectifs = json_decode($devis->objectifs, true);
        }

        // Débogage des données
        \Log::info('Données du devis:', [
            'devis' => $devis->toArray(),
            'objectifs' => $devis->objectifs,
            'analyse_technique' => $devis->analyse_technique
        ]);

        return view('admin.devis.show', compact('devis'));
    }

    public function destroy(Devis $devis)
    {
        DB::beginTransaction();
        try {
            // Vérifier si le devis existe encore
            if (!Devis::find($devis->id)) {
                throw new \Exception('Le devis n\'existe plus');
            }

            // Forcer la suppression immédiate
            $result = DB::table('devis')->where('id', $devis->id)->delete();
            
            if (!$result) {
                throw new \Exception('Échec de la suppression du devis');
            }

            DB::commit();
            
            return redirect()->route('admin.devis.index')
                ->with('success', 'Le devis a été supprimé avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression du devis: ' . $e->getMessage());
            return redirect()->route('admin.devis.index')
                ->with('error', 'Une erreur est survenue lors de la suppression du devis');
        }
    }

    public function downloadPdf($id)
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
    }    public function updateStatus(Request $request, Devis $devis)
    {
        $validatedData = $request->validate([
            'status' => 'required|string|in:en_attente,pending,en_cours,in_progress,accepte,accepted,refuse,rejected'
        ]);

        // Map les anciens status aux nouveaux
        $statusMap = [
            'en_attente' => 'pending',
            'en_cours' => 'in_progress',
            'accepte' => 'accepted',
            'refuse' => 'rejected'
        ];

        $newStatus = $statusMap[$validatedData['status']] ?? $validatedData['status'];
        $devis->update([
            'statut' => $validatedData['status'],
            'status' => $newStatus
        ]);

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
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemandeService;
use App\Traits\NotificationMarkable;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    use NotificationMarkable;    public function index()
    {
        $this->markNotificationsAsRead('App\Notifications\NewServiceRequestNotification');
        $requests = DemandeService::with('service')->latest()->get();
        return view('admin.services.requests', compact('requests'));
    }    public function updateStatus(Request $request, DemandeService $demandeService)
    {
        \Log::info('Début de mise à jour du statut de la demande', [
            'demande_id' => $demandeService->id,
            'ancien_statut' => $demandeService->statut,
            'nouveau_statut' => $request->statut,
            'request_all' => $request->all()
        ]);

        try {
            $validated = $request->validate([
                'statut' => ['required', 'in:' . implode(',', [
                    DemandeService::STATUT_EN_ATTENTE,
                    DemandeService::STATUT_EN_COURS,
                    DemandeService::STATUT_ACCEPTE,
                    DemandeService::STATUT_REFUSE
                ])]
            ]);

            $demandeService->statut = $validated['statut'];
            $saved = $demandeService->save();
            
            \Log::info('Résultat de la sauvegarde', [
                'demande_id' => $demandeService->id,
                'saved' => $saved,
                'nouveau_statut' => $demandeService->fresh()->statut,
                'validation_passed' => true
            ]);

            if (!$saved) {
                throw new \Exception('Échec de la sauvegarde de la demande');
            }

            return back()->with('success', 'Le statut de la demande a été mis à jour avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour du statut', [
                'demande_id' => $demandeService->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour du statut.');
        }
    }

    public function destroy(DemandeService $request)
    {
        // Vérifier si la demande peut être supprimée
        if (!in_array($request->statut, [DemandeService::STATUT_REFUSE, DemandeService::STATUT_ACCEPTE])) {
            return back()->with('error', 'Cette demande ne peut pas être supprimée.');
        }

        // Supprimer la demande
        $request->delete();

        return back()->with('success', 'La demande a été supprimée avec succès.');
    }

    public function adminRequests()
    {
        // Marquer les notifications comme lues
        $this->markNotificationsAsRead('App\Notifications\NewServiceRequestNotification');

        // Récupérer toutes les demandes de service avec leurs relations
        $requests = DemandeService::with(['service', 'user'])
            ->latest()
            ->paginate(10);

        // Retourner la vue avec les données
        return view('admin.services.requests', compact('requests'));
    }
}

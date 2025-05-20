<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemandeService;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    public function index()
    {
        $requests = DemandeService::with('service')->latest()->get();
        return view('admin.services.requests', compact('requests'));
    }

    public function updateStatus(Request $request, DemandeService $demandeService)
    {
        $request->validate([
            'statut' => ['required', 'in:' . implode(',', [
                DemandeService::STATUT_EN_ATTENTE,
                DemandeService::STATUT_EN_COURS,
                DemandeService::STATUT_ACCEPTE,
                DemandeService::STATUT_REFUSE
            ])]
        ]);

        $demandeService->update(['statut' => $request->statut]);

        return back()->with('success', 'Le statut de la demande a été mis à jour avec succès.');
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
}

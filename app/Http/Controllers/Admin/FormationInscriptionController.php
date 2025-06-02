<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormationInscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FormationInscriptionController extends Controller
{    
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }    public function index(Request $request)
    {
        $query = FormationInscription::with('formation');

        if ($request->filled('formation_id')) {
            $query->where('formation_id', $request->formation_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%")
                  ->orWhereHas('formation', function($q) use ($search) {
                      $q->where('titre', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('export')) {
            return $this->exportToExcel($query->get());
        }

        $inscriptions = $query->latest()->paginate(10)->withQueryString();
        $formations = \App\Models\Formation::orderBy('titre')->get();
        
        return view('admin.formations.inscriptions.index', compact('inscriptions', 'formations'));
    }

    protected function exportToExcel($inscriptions)
    {
        // À implémenter si nécessaire
        return back()->with('error', 'Fonctionnalité d\'export en cours de développement');
    }

    public function updateStatus(Request $request, FormationInscription $inscription)
    {
        $request->validate([
            'statut' => 'required|in:acceptee,en_attente,refusee'
        ]);

        $oldStatut = $inscription->statut;
        $inscription->update(['statut' => $request->statut]);

        // Envoi de notification à l'utilisateur
        $message = match($request->statut) {
            'acceptee' => 'Votre inscription a été acceptée.',
            'refusee' => 'Votre inscription a été refusée.',
            'en_attente' => 'Votre inscription est en attente de traitement.',
            default => 'Le statut de votre inscription a été mis à jour.'
        };

        return back()->with('success', 'Le statut de l\'inscription a été mis à jour.');
    }    public function destroy(FormationInscription $inscription)
    {
        if (!in_array($inscription->statut, ['refusee', 'acceptee'])) {
            return back()->with('error', 'Cette inscription ne peut pas être supprimée.');
        }

        // Suppression des fichiers associés
        if ($inscription->acte_naissance_path) {
            Storage::delete($inscription->acte_naissance_path);
        }
        if ($inscription->cni_path) {
            Storage::delete($inscription->cni_path);
        }
        if ($inscription->diplome_path) {
            Storage::delete($inscription->diplome_path);
        }
        
        $inscription->delete();

        return back()->with('success', 'L\'inscription a été supprimée avec succès.');
    }

    public function downloadDocument(FormationInscription $inscription, $type)
    {
        $path = match($type) {
            'acte_naissance' => $inscription->acte_naissance_path,
            'cni' => $inscription->cni_path,
            'diplome' => $inscription->diplome_path,
            default => abort(404)
        };

        if (!Storage::exists($path)) {
            abort(404, 'Document non trouvé');
        }

        return response()->download(storage_path('app/' . $path));
    }
}

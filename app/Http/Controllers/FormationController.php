<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\FormationInscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Events\FormationInscriptionCreated;

class FormationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['inscription']);
    }    public function index()
    {
        $formations = Formation::with('inscriptions')
            ->where('statut', 'active')
            ->where('date_debut', '>', now())
            ->orderBy('date_debut')
            ->get();
        return view('formation', compact('formations'));
    }    public function show()
    {
        $formations = Formation::where('statut', 'active')
            ->whereRaw('(SELECT COUNT(*) FROM formation_inscriptions WHERE formation_id = formations.id) < formations.places_disponibles')
            ->where('date_debut', '>', now())
            ->orderBy('date_debut')
            ->get();
        return view('inscription', compact('formations'));
    }

    public function mesInscriptions()
    {
        $inscriptions = FormationInscription::with('formation')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('formations.mes-inscriptions', compact('inscriptions'));
    }

    public function inscription(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|min:2',
            'email' => 'required|email',
            'telephone' => 'required|string|min:8',
            'formation' => 'required|exists:formations,id',
            'acte_naissance_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'cni_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'diplome_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'autres_documents_paths.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);        // Stocker les fichiers dans le stockage privé
        $acteNaissancePath = $request->file('acte_naissance_path')->store('documents/formations');
        $cniPath = $request->file('cni_path')->store('documents/formations');
        $diplomePath = $request->file('diplome_path')->store('documents/formations');
        
        $autresDocumentsPaths = [];
        if ($request->hasFile('autres_documents_paths')) {
            foreach ($request->file('autres_documents_paths') as $file) {
                $autresDocumentsPaths[] = $file->store('documents/formations');
            }
        }

        // Créer l'inscription
        $inscription = FormationInscription::create([
            'formation_id' => $request->formation,
            'user_id' => auth()->id(),
            'nom' => $request->nom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'acte_naissance_path' => $acteNaissancePath,
            'cni_path' => $cniPath,
            'diplome_path' => $diplomePath,
            'autres_documents_paths' => $autresDocumentsPaths,
            'statut' => 'en_attente'
        ]);

        // Déclencher l'événement
        event(new FormationInscriptionCreated($inscription));

        return redirect()->back()->with('success', 'Votre inscription a été enregistrée avec succès. Nous vous contacterons pour la suite du processus.');
    }    public function downloadFlyer(Formation $formation)
    {
        if (!$formation->flyer || !Storage::disk('public')->exists($formation->flyer)) {
            abort(404, 'Le flyer demandé n\'existe pas.');
        }

        $path = storage_path('app/public/' . $formation->flyer);
        return response()->download($path);
    }    public function downloadDocument(FormationInscription $inscription, $type)
    {
        // Vérifier que l'utilisateur est authentifié et autorisé
        if (!auth()->check() || (auth()->id() !== $inscription->user_id && !auth()->user()->isAdmin())) {
            abort(403, 'Non autorisé');
        }

        // Déterminer le chemin du fichier en fonction du type
        $path = match($type) {
            'acte_naissance' => $inscription->acte_naissance_path,
            'cni' => $inscription->cni_path,
            'diplome' => $inscription->diplome_path,
            default => abort(404)
        };

        // Vérifier si le fichier existe
        if (!Storage::exists($path)) {
            abort(404, 'Document non trouvé');
        }

        // Retourner le fichier
        return response()->download(storage_path('app/' . $path));}
}

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
    }

    public function index()
    {
        $formations = Formation::where('statut', 'active')->get();
        return view('formation', compact('formations'));
    }

    public function show()
    {
        $formations = Formation::where('statut', 'active')->get();
        return view('inscription', compact('formations'));
    }

    public function inscription(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|min:2',
            'email' => 'required|email',
            'telephone' => 'required|string|min:8',
            'formation' => 'required|exists:formations,id',
            'acte_naissance_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'cni_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'diplome_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'autres_documents_paths.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        // Stocker les fichiers
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
    }
}

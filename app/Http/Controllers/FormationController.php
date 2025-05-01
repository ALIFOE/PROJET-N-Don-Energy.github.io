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
        $this->middleware('auth')->only(['inscription', 'mesInscriptions']);
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

        // Stocker les fichiers dans le dossier public
        $acteNaissancePath = $request->file('acte_naissance_path')->store('public/documents/formations');
        $cniPath = $request->file('cni_path')->store('public/documents/formations');
        $diplomePath = $request->file('diplome_path')->store('public/documents/formations');
        
        $autresDocumentsPaths = [];
        if ($request->hasFile('autres_documents_paths')) {
            foreach ($request->file('autres_documents_paths') as $file) {
                $autresDocumentsPaths[] = $file->store('public/documents/formations');
            }
        }

        // Créer l'inscription en retirant 'public/' du chemin stocké
        $inscription = FormationInscription::create([
            'formation_id' => $request->formation,
            'user_id' => auth()->id(),
            'nom' => $request->nom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'acte_naissance_path' => str_replace('public/', '', $acteNaissancePath),
            'cni_path' => str_replace('public/', '', $cniPath),
            'diplome_path' => str_replace('public/', '', $diplomePath),
            'autres_documents_paths' => array_map(function($path) {
                return str_replace('public/', '', $path);
            }, $autresDocumentsPaths),
            'statut' => 'en_attente'
        ]);

        // Déclencher l'événement
        event(new FormationInscriptionCreated($inscription));

        return redirect()->route('formation')
            ->with('success', 'Votre inscription a été enregistrée avec succès ! Nous vous contacterons prochainement.');
    }

    public function mesInscriptions()
    {
        $inscriptions = FormationInscription::with('formation')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('formation.mes-inscriptions', compact('inscriptions'));
    }
}

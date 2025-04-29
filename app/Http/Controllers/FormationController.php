<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormationInscription;

class FormationController extends Controller
{
    /**
     * Handle the inscription form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function inscription(Request $request): \Illuminate\Http\RedirectResponse
    {
        // Validation des données du formulaire
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:20',
            'formation' => 'required|integer|exists:formations,id',
            'message' => 'nullable|string|max:1000',
            'acte_naissance_path' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'cni_path' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'diplome_path' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'autres_documents_paths.*' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        // Gestion des fichiers téléchargés
        $acteNaissancePath = $request->file('acte_naissance_path')->store('documents/actes_naissance', 'public');
        $cniPath = $request->file('cni_path')->store('documents/cni', 'public');
        $diplomePath = $request->file('diplome_path')->store('documents/diplomes', 'public');

        $autresDocumentsPaths = [];
        if ($request->hasFile('autres_documents_paths')) {
            foreach ($request->file('autres_documents_paths') as $file) {
                $autresDocumentsPaths[] = $file->store('documents/autres', 'public');
            }
        }

        // Enregistrement dans la base de données
        FormationInscription::create([
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'formation_id' => $validated['formation'],
            'message' => $validated['message'] ?? null,
            'user_id' => auth()->id(),
            'acte_naissance_path' => $acteNaissancePath,
            'cni_path' => $cniPath,
            'diplome_path' => $diplomePath,
            'autres_documents_paths' => json_encode($autresDocumentsPaths),
            'statut' => 'en_attente',
        ]);

        return redirect()->route('formation.inscription.page')->with('success', 'Votre inscription a été enregistrée avec succès.');
    }

    /**
     * Afficher le formulaire d'inscription avec les formations disponibles.
     *
     * @return \Illuminate\View\View
     */
    public function showInscriptionForm()
    {
        $formations = \App\Models\Formation::all();
        return view('inscription', compact('formations'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogActivite;

class DevisController extends Controller
{
    public function create()
    {
        return view('devis.create');
    }

    public function store(Request $request)
    {
        // Validez et traitez les données ici
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        // Logique pour enregistrer ou envoyer les données

        // Enregistrer une activité pour le client
        // Vérifiez si l'utilisateur est authentifié avant de créer une activité
        if (auth()->check()) {
            LogActivite::create([
                'user_id' => auth()->id(),
                'action' => 'création',
                'table' => 'devis',
                'id_table' => '0', // ou l'ID du devis si vous l'avez créé
                'description' => 'Un devis a été soumis avec succès.',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        } else {
            // Gérer le cas où l'utilisateur n'est pas authentifié (facultatif)
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour soumettre un devis.');
        }

        return redirect()->route('devis.create')->with('success', 'Votre demande a été envoyée avec succès.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Onduleur;
use Illuminate\Http\Request;

class OnduleurConfigController extends Controller
{
    /**
     * Afficher la configuration des onduleurs
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $onduleurs = Onduleur::with('installation')->get();
        return view('onduleurs.config', compact('onduleurs'));
    }

    /**
     * Sauvegarder la configuration des onduleurs
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request)
    {
        $validated = $request->validate([
            'onduleur_id' => 'required|exists:onduleurs,id',
            'parametres' => 'required|array'
        ]);

        $onduleur = Onduleur::findOrFail($validated['onduleur_id']);
        
        // Mettre à jour les paramètres de l'onduleur
        foreach ($validated['parametres'] as $key => $value) {
            $onduleur->$key = $value;
        }
        
        $onduleur->save();

        return redirect()->back()->with('success', 'Configuration mise à jour avec succès');
    }
}
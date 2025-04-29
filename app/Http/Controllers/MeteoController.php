<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeteoController extends Controller
{
    public function showAlerteConfig()
    {
        // Récupérer la configuration actuelle des alertes pour l'utilisateur
        $config = Auth::user()->meteo_config ?? [
            'temp_min' => '',
            'temp_max' => '',
            'wind_speed' => '',
            'rain_probability' => '',
            'notify_email' => false,
            'notify_sms' => false,
        ];

        return view('meteo.alertes.config', compact('config'));
    }

    public function saveAlerteConfig(Request $request)
    {
        $validated = $request->validate([
            'temp_min' => 'required|numeric|between:-30,50',
            'temp_max' => 'required|numeric|between:-30,50',
            'wind_speed' => 'required|numeric|min:0|max:200',
            'rain_probability' => 'required|numeric|between:0,100',
            'notify_email' => 'nullable|boolean',
            'notify_sms' => 'nullable|boolean',
        ]);

        // Sauvegarder la configuration dans les préférences de l'utilisateur
        $user = Auth::user();
        $user->meteoConfig = $validated;
        $user->save();

        return redirect()->back()->with('success', 'Configuration des alertes météo mise à jour avec succès.');
    }

    public function index()
    {
        return view('meteo.index');
    }

    public function getDonneesActuelles()
    {
        // À implémenter : récupération des données météo actuelles
        return response()->json([
            'temperature' => 25,
            'wind_speed' => 10,
            'rain_probability' => 30,
            // ...autres données
        ]);
    }
}
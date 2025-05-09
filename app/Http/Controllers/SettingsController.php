<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class SettingsController extends Controller
{
    public function index()
    {
        return view('parametres');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
            'notifications' => ['nullable', 'array'],
        ]);

        User::where('id', Auth::id())->update([
            'name' => $request->name,
            'email' => $request->email,
            'notification_preferences' => $request->notifications ?? [],
        ]);

        return response()->json([
            'message' => 'Profil mis à jour avec succès'
        ]);
    }

    public function security(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', Password::defaults()],
            'new_password_confirmation' => ['required', 'same:new_password'],
        ]);

        User::where('id', Auth::id())->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'message' => 'Mot de passe mis à jour avec succès'
        ]);
    }

    public function display(Request $request)
    {
        $request->validate([
            'theme' => ['required', 'string', 'in:light,dark,system'],
            'language' => ['required', 'string', 'in:fr,en,es'],
        ]);

        User::where('id', Auth::id())->update([
            'theme_preference' => $request->theme,
            'language' => $request->language
        ]);

        return response()->json([
            'message' => 'Préférences d\'affichage mises à jour avec succès'
        ]);
    }

    public function toggleTwoFactor(Request $request)
    {
        $request->validate([
            'enabled' => ['required', 'boolean'],
        ]);
        
        if ($request->enabled) {
            // Logique pour activer 2FA
            // Génération du secret et du QR code
            $secret = '123456'; // À remplacer par une vraie génération de secret
            $qrCode = 'data:image/png;base64,...'; // À remplacer par un vrai QR code

            User::where('id', Auth::id())->update([
                'two_factor_enabled' => true,
                'two_factor_secret' => $secret
            ]);

            return response()->json([
                'message' => 'Authentification à deux facteurs activée',
                'qrCode' => $qrCode
            ]);
        } else {
            User::where('id', Auth::id())->update([
                'two_factor_enabled' => false,
                'two_factor_secret' => null
            ]);

            return response()->json([
                'message' => 'Authentification à deux facteurs désactivée'
            ]);
        }
    }
}
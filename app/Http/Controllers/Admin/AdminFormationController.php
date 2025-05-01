<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormationInscription;
use App\Models\User;
use App\Notifications\FormationStatusChanged;
use Illuminate\Http\Request;

class AdminFormationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $inscriptions = FormationInscription::with(['formation', 'user'])
            ->latest()
            ->paginate(20);

        return view('admin.formations.inscriptions.index', compact('inscriptions'));
    }

    public function show(FormationInscription $inscription)
    {
        return view('admin.formations.inscriptions.show', compact('inscription'));
    }

    public function updateStatus(Request $request, FormationInscription $inscription)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,acceptee,refusee'
        ]);

        $inscription->update([
            'statut' => $request->statut
        ]);

        // Notifier l'utilisateur du changement de statut
        $user = User::find($inscription->user_id);
        if ($user) {
            $user->notify(new \App\Notifications\FormationStatusChanged($inscription));
        }

        return redirect()->back()
            ->with('success', 'Le statut de l\'inscription a été mis à jour avec succès.');
    }
}
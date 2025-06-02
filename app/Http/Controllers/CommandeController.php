<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class CommandeController extends Controller
{    public function index()
    {
        $commandes = Order::with('product')
            ->where('user_id', auth()->id())
            ->where('hidden', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mes-commandes', compact('commandes'));
    }

    public function show(Order $commande)
    {
        // Vérifier que l'utilisateur est bien le propriétaire de la commande
        if ($commande->user_id !== auth()->id()) {
            abort(403);
        }

        return view('commandes.show', compact('commande'));
    }

    public function delete(Order $commande)
    {
        // Vérifier que l'utilisateur est bien le propriétaire de la commande
        if ($commande->user_id !== auth()->id()) {
            abort(403);
        }        // Mettre à jour le statut "hidden" de la commande plutôt que de la supprimer
        $commande->update(['hidden' => true]);

        return redirect()->route('mes-commandes')
            ->with('success', 'La commande a été retirée de votre liste mais continuera d\'être traitée.');
    }
}

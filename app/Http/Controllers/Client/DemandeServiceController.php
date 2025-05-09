<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\DemandeService;
use Illuminate\Http\Request;

class DemandeServiceController extends Controller
{
    /**
     * Affiche la liste des demandes de services du client connecté
     */
    public function index()
    {
        $demandes = DemandeService::where('user_id', auth()->id())
            ->with('service')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('client.demandes-services.index', compact('demandes'));
    }
}

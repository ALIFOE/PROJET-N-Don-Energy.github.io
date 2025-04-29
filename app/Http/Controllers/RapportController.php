<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RapportController extends Controller
{
    public function index()
    {
        // Pour l'instant, nous retournons simplement la vue
        // Plus tard, nous ajouterons la logique pour générer les rapports
        return view('rapports-analyses');
    }
}
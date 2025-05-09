<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OptimisationController extends Controller
{
    public function index()
    {
        return view('services.optimisation', [
            'title' => 'Optimisation de rendement',
            'description' => 'Service d\'optimisation du rendement de vos installations solaires'
        ]);
    }
}
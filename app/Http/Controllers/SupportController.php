<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index()
    {
        return view('services.support', [
            'title' => 'Assistance technique',
            'description' => 'Service d\'assistance technique pour vos installations solaires'
        ]);
    }
}
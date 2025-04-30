<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Installation;

class SystemeIntelligentController extends Controller
{
    public function index()
    {
        return view('systeme-intelligent');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RapportsController extends Controller
{
    public function index()
    {
        return view('rapports.index');
    }
}

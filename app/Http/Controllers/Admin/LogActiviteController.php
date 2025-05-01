<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogActivite;
use Illuminate\Http\Request;

class LogActiviteController extends Controller
{
    public function index()
    {
        $activites = LogActivite::with('user')
                    ->orderBy('created_at', 'desc')
                    ->paginate(50);
                    
        return view('admin.log-activites.index', compact('activites'));
    }
}

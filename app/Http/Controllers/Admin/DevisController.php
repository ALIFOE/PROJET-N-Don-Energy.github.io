<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Devis;
use Illuminate\Http\Request;

class DevisController extends Controller
{
    public function index()
    {
        $devis = Devis::latest()->paginate(10);
        return view('admin.devis.index', compact('devis'));
    }

    public function show(Devis $devis)
    {
        return view('admin.devis.show', compact('devis'));
    }

    public function destroy(Devis $devis)
    {
        $devis->delete();
        return redirect()->route('admin.devis.index')
            ->with('success', 'Le devis a été supprimé avec succès.');
    }
}
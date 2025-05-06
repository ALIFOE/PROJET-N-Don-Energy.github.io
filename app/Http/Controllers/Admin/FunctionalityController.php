<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Functionality;
use Illuminate\Http\Request;

class FunctionalityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $functionalities = Functionality::latest()->paginate(10);
        return view('admin.functionalities.index', compact('functionalities'));
    }

    public function create()
    {
        return view('admin.functionalities.create');
    }    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'icone' => 'nullable|string|max:50',
            'statut' => 'required|boolean'
        ]);

        Functionality::create($validatedData);

        return redirect()->route('admin.functionalities.index')
            ->with('success', 'Fonctionnalité créée avec succès.');
    }

    public function edit(Functionality $functionality)
    {
        return view('admin.functionalities.edit', compact('functionality'));
    }    public function update(Request $request, Functionality $functionality)
    {
        $validatedData = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'icone' => 'nullable|string|max:50',
            'statut' => 'required|boolean'
        ]);

        $functionality->update($validatedData);

        return redirect()->route('admin.functionalities.index')
            ->with('success', 'Fonctionnalité mise à jour avec succès.');
    }

    public function destroy(Functionality $functionality)
    {
        $functionality->delete();

        return redirect()->route('admin.functionalities.index')
            ->with('success', 'Fonctionnalité supprimée avec succès.');
    }
}
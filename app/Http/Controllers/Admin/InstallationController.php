<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Installation;
use App\Models\Devis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstallationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $installations = Installation::with(['user', 'devis'])
            ->latest()
            ->paginate(10);
        return view('admin.installations.index', compact('installations'));
    }

    public function create()
    {
        return view('admin.installations.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type_installation' => 'required|string',
            'puissance' => 'required|numeric|min:0',
            'adresse' => 'required|string',
            'ville' => 'required|string',
            'code_postal' => 'required|string',
            'date_installation' => 'required|date',
            'statut' => 'required|in:en_attente,en_cours,terminee,annulee',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('documents')) {
            $documents = [];
            foreach ($request->file('documents') as $document) {
                $documents[] = $document->store('installations/documents');
            }
            $validatedData['documents'] = $documents;
        }

        Installation::create($validatedData);

        return redirect()->route('admin.installations.index')
            ->with('success', 'Installation créée avec succès.');
    }

    public function edit(Installation $installation)
    {
        return view('admin.installations.edit', compact('installation'));
    }

    public function update(Request $request, Installation $installation)
    {
        $validatedData = $request->validate([
            'type_installation' => 'required|string',
            'puissance' => 'required|numeric|min:0',
            'adresse' => 'required|string',
            'ville' => 'required|string',
            'code_postal' => 'required|string',
            'date_installation' => 'required|date',
            'statut' => 'required|in:en_attente,en_cours,terminee,annulee',
            'nouveaux_documents' => 'nullable|array',
            'nouveaux_documents.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('nouveaux_documents')) {
            $documents = $installation->documents ?? [];
            foreach ($request->file('nouveaux_documents') as $document) {
                $documents[] = $document->store('installations/documents');
            }
            $validatedData['documents'] = $documents;
        }

        $installation->update($validatedData);

        return redirect()->route('admin.installations.index')
            ->with('success', 'Installation mise à jour avec succès.');
    }

    public function destroy(Installation $installation)
    {
        // Supprimer les documents associés
        if (!empty($installation->documents)) {
            foreach ($installation->documents as $document) {
                Storage::delete($document);
            }
        }

        $installation->delete();

        return redirect()->route('admin.installations.index')
            ->with('success', 'Installation supprimée avec succès.');
    }

    public function pending()
    {
        $devis = Devis::with(['user'])
            ->where('statut', 'en_attente')
            ->latest()
            ->paginate(10);

        return view('admin.installations.pending', compact('devis'));
    }

    public function show(Installation $installation)
    {
        return view('admin.installations.show', compact('installation'));
    }
}

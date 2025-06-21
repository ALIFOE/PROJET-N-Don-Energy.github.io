<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\FormationInscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FormationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }    public function index()
    {
        $formations = Formation::withCount('inscriptions')
            ->latest()
            ->paginate(10);
        return view('admin.formations.index', compact('formations'));
    }

    public function create()
    {
        return view('admin.formations.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'prix' => 'required|numeric|min:0',
            'places_disponibles' => 'required|integer|min:1', 
            'prerequis' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'flyer' => 'nullable|mimes:pdf|max:5120', // Ajout du flyer, max 5MB
            'statut' => 'required|in:active,inactive'
        ]);

        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('formations', 'public');
        }

        // Stocker le flyer s'il existe
        if ($request->hasFile('flyer')) {
            $validatedData['flyer'] = $request->file('flyer')->store('formations/flyers', 'public');
        }

        Formation::create($validatedData);

        return redirect()->route('admin.formations.index')
            ->with('success', 'Formation créée avec succès.');
    }

    public function edit(Formation $formation)
    {
        return view('admin.formations.edit', compact('formation'));
    }

    public function update(Request $request, Formation $formation)
    {
        $validatedData = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'prix' => 'required|numeric|min:0',
            'places_disponibles' => 'required|integer|min:1',
            'prerequis' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'flyer' => 'nullable|mimes:pdf|max:5120', // Ajout du flyer, max 5MB
            'statut' => 'required|in:active,inactive'
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($formation->image) {
                Storage::disk('public')->delete($formation->image);
            }
            $validatedData['image'] = $request->file('image')->store('formations', 'public');
        }

        // Gérer le flyer
        if ($request->hasFile('flyer')) {
            // Supprimer l'ancien flyer s'il existe
            if ($formation->flyer) {
                Storage::disk('public')->delete($formation->flyer);
            }
            $validatedData['flyer'] = $request->file('flyer')->store('formations/flyers', 'public');
        }

        $formation->update($validatedData);

        return redirect()->route('admin.formations.index')
            ->with('success', 'Formation mise à jour avec succès.');
    }

    public function destroy(Formation $formation)
    {
        // Supprimer l'image si elle existe
        if ($formation->image) {
            Storage::disk('public')->delete($formation->image);
        }

        // Supprimer le flyer s'il existe
        if ($formation->flyer) {
            Storage::disk('public')->delete($formation->flyer);
        }

        $formation->delete();

        return redirect()->route('admin.formations.index')
            ->with('success', 'Formation supprimée avec succès.');
    }    public function show(Formation $formation)
    {
        $inscriptions = $formation->inscriptions()->with('user')->latest()->get();
        return view('admin.formations.show', compact('formation', 'inscriptions'));
    }    public function inscriptions()
    {
        $inscriptions = FormationInscription::with(['formation', 'user'])
            ->latest()
            ->paginate(10);
            
        return view('admin.formations.inscriptions', compact('inscriptions'));
    }    public function destroyInscription(FormationInscription $inscription)
    {
        // Supprimer les fichiers associés
        if ($inscription->acte_naissance_path) {
            Storage::disk('public')->delete($inscription->acte_naissance_path);
        }
        if ($inscription->cni_path) {
            Storage::disk('public')->delete($inscription->cni_path);
        }
        if ($inscription->diplome_path) {
            Storage::disk('public')->delete($inscription->diplome_path);
        }
        if ($inscription->autres_documents_paths) {
            foreach ($inscription->autres_documents_paths as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $inscription->delete();

        return redirect()->route('admin.formations.inscriptions')
            ->with('success', 'Inscription supprimée avec succès');
    }

    public function downloadFlyer(Formation $formation)
    {
        if (!$formation->flyer || !Storage::disk('public')->exists($formation->flyer)) {
            abort(404, 'Le flyer demandé n\'existe pas.');
        }

        return response()->download(storage_path('app/public/' . $formation->flyer));
    }
}
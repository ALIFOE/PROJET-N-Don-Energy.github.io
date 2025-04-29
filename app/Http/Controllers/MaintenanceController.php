<?php

namespace App\Http\Controllers;

use App\Models\Installation;
use App\Models\MaintenanceTask;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = MaintenanceTask::with('installation')
            ->whereHas('installation', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->get();
            
        $installations = Installation::where('user_id', auth()->id())->get();
        return view('maintenance-predictive', compact('maintenances', 'installations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'installation_id' => 'required|exists:installations,id',
            'type' => 'required|in:preventive,corrective,predictive',
            'description' => 'required|string',
            'date_prevue' => 'required|date|after:today',
        ]);

        $maintenance = new MaintenanceTask();
        $maintenance->installation_id = $validated['installation_id'];
        $maintenance->user_id = auth()->id(); // Correction ici
        $maintenance->type = $validated['type'];
        $maintenance->description = $validated['description'];
        $maintenance->date = $validated['date_prevue'];
        $maintenance->statut = 'planifiee';
        $maintenance->save();

        return redirect()->route('maintenance-predictive')
            ->with('success', 'Maintenance planifiée avec succès');
    }

    public function edit($id)
    {
        $maintenance = MaintenanceTask::findOrFail($id);
        $installations = Installation::where('user_id', auth()->id())->get();
        return view('maintenance.edit', compact('maintenance', 'installations'));
    }

    public function update(Request $request, $id)
    {
        $maintenance = MaintenanceTask::findOrFail($id);
        
        $validated = $request->validate([
            'installation_id' => 'required|exists:installations,id',
            'type' => 'required|in:preventive,corrective,predictive',
            'description' => 'required|string|max:1000',
            'date_prevue' => 'required|date|after:today',
            'statut' => 'required|in:planifiee,en_cours,terminee,annulee',
            'notes' => 'nullable|string|max:1000'
        ]);

        $maintenance->update([
            'installation_id' => $validated['installation_id'],
            'type' => $validated['type'],
            'description' => $validated['description'],
            'date' => $validated['date_prevue'],
            'statut' => $validated['statut'],
            'notes' => $validated['notes']
        ]);

        // Enregistrer l'activité
        activity()
            ->performedOn($maintenance)
            ->causedBy(auth()->user())
            ->withProperties([
                'type' => $validated['type'],
                'statut' => $validated['statut'],
                'installation' => $maintenance->installation->nom
            ])
            ->log('modification');

        return redirect()->route('maintenance-predictive')
            ->with('success', 'Maintenance mise à jour avec succès');
    }

    public function destroy($id)
    {
        $maintenance = MaintenanceTask::findOrFail($id);
        $maintenance->delete();

        return redirect()->route('maintenance-predictive')
            ->with('success', 'Maintenance supprimée avec succès');
    }
}

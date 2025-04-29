<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dimensionnement;
use App\Models\Installation;
use App\Models\Onduleur;
use App\Models\MaintenanceTask;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LogActiviteDetailController extends Controller
{
    public function show($table, $id)
    {
        $model = match ($table) {
            'dimensionnements' => Dimensionnement::find($id),
            'installations' => Installation::find($id),
            'onduleurs' => Onduleur::find($id),
            'maintenance_tasks' => MaintenanceTask::find($id),
            default => null
        };

        if (!$model || $model->user_id !== auth()->id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        // Formater les détails selon le type d'objet
        $details = match ($table) {
            'dimensionnements' => [
                'Type d\'installation' => $model->type_installation,
                'Surface disponible' => $model->surface_disponible . ' m²',
                'Puissance installée' => $model->puissance_installee . ' kWc',
                'Budget' => number_format($model->budget, 0, ',', ' ') . ' CFA',
                'Statut' => ucfirst(str_replace('_', ' ', $model->statut))
            ],
            'installations' => [
                'Nom' => $model->nom,
                'Puissance totale' => $model->puissance_totale . ' kWc',
                'Surface totale' => $model->surface_totale . ' m²',
                'Nombre de panneaux' => $model->nombre_panneaux,
                'Date d\'installation' => $model->date_installation->format('d/m/Y')
            ],
            'onduleurs' => [
                'Modèle' => $model->modele,
                'Numéro de série' => $model->numero_serie,
                'Statut' => $model->est_connecte ? 'Connecté' : 'Déconnecté'
            ],
            'maintenance_tasks' => [
                'Type' => ucfirst($model->type),
                'Description' => $model->description,
                'Date prévue' => $model->date->format('d/m/Y'),
                'Priorité' => ucfirst($model->priorite),
                'Statut' => ucfirst(str_replace('_', ' ', $model->statut))
            ],
            default => []
        };

        return response()->json($details);
    }
}
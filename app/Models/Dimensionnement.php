<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dimensionnement extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'nom',
        'email',
        'telephone',
        'adresse',
        'ville',
        'code_postal',
        'pays',
        'surface_disponible',
        'consommation_annuelle',
        'puissance_installee',
        'production_annuelle_estimee',
        'economie_annuelle_estimee',
        'taux_autoconsommation',
        'taux_autoproduction',
        'prix_kwh',
        'budget_total',
        'duree_amortissement',
        'rentabilite',
        'statut',
        'type_logement',
        'surface_toiture',
        'orientation',
        'budget',
        'type_installation',
        'equipements',
        'objectifs',
        'facture_annuelle',
        'fournisseur',
        'nb_personnes'
    ];

    protected $casts = [
        'equipements' => 'array',
        'objectifs' => 'array',
        'surface_disponible' => 'float',
        'consommation_annuelle' => 'float',
        'puissance_installee' => 'float',
        'production_annuelle_estimee' => 'float',
        'economie_annuelle_estimee' => 'float',
        'taux_autoconsommation' => 'float',
        'taux_autoproduction' => 'float',
        'prix_kwh' => 'float',
        'budget_total' => 'float',
        'duree_amortissement' => 'float',
        'rentabilite' => 'float',
        'surface_toiture' => 'float',
        'budget' => 'float',
        'facture_annuelle' => 'float',
        'nb_personnes' => 'integer'
    ];

    /**
     * Relation avec l'utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function getDescription(string $action, $model): string
    {
        switch ($action) {
            case 'création':
                return "Nouvelle demande de dimensionnement créée pour une installation de type {$model->type_installation}";
            case 'modification':
                return "Mise à jour de la demande de dimensionnement n°{$model->id}";
            case 'suppression':
                return "Suppression de la demande de dimensionnement n°{$model->id}";
            default:
                return parent::getDescription($action, $model);
        }
    }
}

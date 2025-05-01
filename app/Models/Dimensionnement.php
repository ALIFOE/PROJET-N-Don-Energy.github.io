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
        'type_logement',
        'surface_toiture',
        'orientation',
        'facture_annuelle',
        'fournisseur',
        'nb_personnes',
        'budget',
        'type_installation',
        'equipements',
        'objectifs',
        'statut',
        'puissance_installee',
        'production_annuelle_estimee',
        'economie_annuelle_estimee',
        'taux_autoconsommation',
        'taux_autoproduction',
        'prix_kwh',
        'duree_amortissement',
        'rentabilite'
    ];

    protected $casts = [
        'equipements' => 'array',
        'objectifs' => 'array',
        'surface_toiture' => 'float',
        'facture_annuelle' => 'float',
        'budget' => 'float',
        'nb_personnes' => 'integer',
        'puissance_installee' => 'float',
        'production_annuelle_estimee' => 'float',
        'economie_annuelle_estimee' => 'float',
        'taux_autoconsommation' => 'float',
        'taux_autoproduction' => 'float',
        'prix_kwh' => 'float',
        'duree_amortissement' => 'float',
        'rentabilite' => 'float'
    ];

    /**
     * Relation avec l'utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setEquipementsAttribute($value)
    {
        $this->attributes['equipements'] = is_array($value) ? json_encode($value) : $value;
    }

    public function setObjectifsAttribute($value)
    {
        $this->attributes['objectifs'] = is_array($value) ? json_encode($value) : $value;
    }

    public function getEquipementsAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function getObjectifsAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
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

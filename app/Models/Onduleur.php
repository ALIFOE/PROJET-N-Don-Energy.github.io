<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Onduleur extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'installation_id',
        'modele',
        'marque',
        'numero_serie',
        'puissance_nominale',
        'est_connecte',
        'date_installation',
        'dernier_entretien',
        'prochain_entretien',
        'statut',
        'duree_recherche'
    ];

    protected $casts = [
        'est_connecte' => 'boolean',
        'date_installation' => 'date',
        'dernier_entretien' => 'date',
        'prochain_entretien' => 'date',
        'puissance_nominale' => 'decimal:2',
        'duree_recherche' => 'integer'
    ];

    public function installation()
    {
        return $this->belongsTo(Installation::class);
    }

    public function donnees()
    {
        return $this->hasMany(InverterData::class);
    }

    protected static function getDescription(string $action, $model): string
    {
        switch ($action) {
            case 'création':
                return "Nouvel onduleur {$model->modele} ajouté (S/N: {$model->numero_serie})";
            case 'modification':
                return "Modification de l'onduleur {$model->modele} (S/N: {$model->numero_serie})";
            case 'suppression':
                return "Suppression de l'onduleur {$model->modele} (S/N: {$model->numero_serie})";
            default:
                return parent::getDescription($action, $model);
        }
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonneeProduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'installation_id',
        'onduleur_id',
        'date_heure',
        'puissance_instantanee',
        'energie_jour',
        'energie_mois',
        'energie_annee',
        'energie_totale',
        'rendement',
        'temperature',
        'irradiance',
        'niveau_batterie',
        'code_erreur',
        'code_avertissement'
    ];

    protected $casts = [
        'date_heure' => 'datetime',
        'puissance_instantanee' => 'float',
        'energie_jour' => 'float',
        'energie_mois' => 'float',
        'energie_annee' => 'float',
        'energie_totale' => 'float',
        'rendement' => 'float',
        'temperature' => 'float',
        'irradiance' => 'float',
        'niveau_batterie' => 'float'
    ];

    public function installation()
    {
        return $this->belongsTo(Installation::class);
    }

    public function onduleur()
    {
        return $this->belongsTo(Onduleur::class);
    }
}

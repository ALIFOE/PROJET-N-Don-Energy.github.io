<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{    protected $fillable = [
        'titre',
        'description',
        'date_debut',
        'date_fin',
        'prix',
        'places_disponibles',
        'prerequis',
        'image',
        'statut'
    ];

    // Une formation peut avoir plusieurs inscriptions
    public function inscriptions()
    {
        return $this->hasMany(FormationInscription::class);
    }
}

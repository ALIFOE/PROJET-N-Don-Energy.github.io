<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'date_debut',
        'date_fin',
        'prix',
        'places_disponibles',
        'prerequis',
        'image',
        'flyer',
        'niveau',
        'statut'
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'prix' => 'float',
        'places_disponibles' => 'integer',
        'statut' => 'string'
    ];

    // Une formation peut avoir plusieurs inscriptions
    public function inscriptions()
    {
        return $this->hasMany(FormationInscription::class);
    }
}

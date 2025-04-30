<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    protected $fillable = [
        'nom',
        'description',
        'duree',
        'niveau',
        'prix',
        'statut'
    ];

    // Une formation peut avoir plusieurs inscriptions
    public function inscriptions()
    {
        return $this->hasMany(FormationInscription::class);
    }
}

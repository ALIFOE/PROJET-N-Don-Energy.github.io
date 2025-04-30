<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panneau extends Model
{
    protected $table = 'panels';
    
    protected $fillable = [
        'type',
        'capacite_wc',
        'surface',
        'rendement',
        'fabricant',
        'modele',
        'garantie_annees'
    ];

    protected $casts = [
        'capacite_wc' => 'integer',
        'surface' => 'float',
        'rendement' => 'float',
        'garantie_annees' => 'integer'
    ];

    public function installations()
    {
        return $this->hasMany(Installation::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonneeMeteo extends Model
{
    use HasFactory;

    protected $fillable = [
        'installation_id',
        'date_heure',
        'temperature',
        'humidite',
        'vitesse_vent',
        'direction_vent',
        'irradiation',
        'ensoleillement'
    ];

    protected $casts = [
        'date_heure' => 'datetime',
        'temperature' => 'float',
        'humidite' => 'float',
        'vitesse_vent' => 'float',
        'direction_vent' => 'float',
        'irradiation' => 'float',
        'ensoleillement' => 'float'
    ];

    public function installation()
    {
        return $this->belongsTo(Installation::class);
    }
}

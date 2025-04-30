<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'adresse',
        'type_batiment',
        'facture_mensuelle',
        'consommation_annuelle',
        'type_toiture',
        'orientation',
        'objectifs',
        'message',
        'analyse_technique'
    ];

    protected $casts = [
        'objectifs' => 'array',
        'analyse_technique' => 'array',
        'facture_mensuelle' => 'decimal:2',
        'consommation_annuelle' => 'decimal:2'
    ];
}
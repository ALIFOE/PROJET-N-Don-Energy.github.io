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
        'analyse_technique',
        'statut'
    ];

    protected $casts = [
        'objectifs' => 'array',
        'analyse_technique' => 'array',
        'facture_mensuelle' => 'decimal:2',
        'consommation_annuelle' => 'decimal:2'
    ];

    public function getStatusLabelAttribute()
    {
        return [
            'en_attente' => 'En attente',
            'en_cours' => 'En cours',
            'accepte' => 'Accepté',
            'refuse' => 'Refusé'
        ][$this->statut] ?? 'Inconnu';
    }

    public function getStatusColorAttribute()
    {
        return [
            'en_attente' => 'yellow',
            'en_cours' => 'blue',
            'accepte' => 'green',
            'refuse' => 'red'
        ][$this->statut] ?? 'gray';
    }

    public function getNomCompletAttribute()
    {
        return "{$this->nom} {$this->prenom}";
    }
}
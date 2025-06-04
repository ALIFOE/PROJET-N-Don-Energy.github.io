<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    use HasFactory;    protected $fillable = [
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
        'statut',
        'status'
    ];

    protected $casts = [
        'objectifs' => 'array',
        'analyse_technique' => 'array',
        'facture_mensuelle' => 'decimal:2',
        'consommation_annuelle' => 'decimal:2'
    ];    public function getStatusLabelAttribute()
    {
        $status = $this->status ?? $this->statut;
        return [
            'en_attente' => 'En attente',
            'pending' => 'En attente',
            'en_cours' => 'En cours',
            'in_progress' => 'En cours',
            'accepte' => 'Accepté',
            'accepted' => 'Accepté',
            'refuse' => 'Refusé',
            'rejected' => 'Refusé'
        ][$status] ?? 'Inconnu';
    }

    public function getStatusColorAttribute()
    {
        $status = $this->status ?? $this->statut;
        return [
            'en_attente' => 'yellow',
            'pending' => 'yellow',
            'en_cours' => 'blue',
            'in_progress' => 'blue',
            'accepte' => 'green',
            'accepted' => 'green',            'refuse' => 'red',
            'rejected' => 'red'
        ][$status] ?? 'gray';
    }

    public function getNomCompletAttribute()
    {
        return "{$this->nom} {$this->prenom}";
    }
}
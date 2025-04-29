<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'telephone',
        'adresse',
        'ville',
        'code_postal',
        'pays',
        'role',
        'est_actif'
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    protected $casts = [
        'date_inscription' => 'datetime',
        'derniere_connexion' => 'datetime',
        'est_actif' => 'boolean',
    ];

    public function installations()
    {
        return $this->hasMany(Installation::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'technicien_id');
    }

    public function messagesEnvoyes()
    {
        return $this->hasMany(Message::class, 'expediteur_id');
    }

    public function messagesRecus()
    {
        return $this->hasMany(Message::class, 'destinataire_id');
    }

    public function avis()
    {
        return $this->hasMany(Avis::class);
    }
}

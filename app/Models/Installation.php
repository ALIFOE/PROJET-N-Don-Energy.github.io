<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Installation extends Model
{
    use HasFactory, LogsActivity, Notifiable;

    protected $fillable = [
        'utilisateur_id',
        'nom',
        'date_installation',
        'puissance_totale',
        'surface_totale',
        'nombre_panneaux',
        'latitude',
        'longitude',
        'orientation',
        'inclinaison',
        'description',
        'statut',
        'cout_installation',
        'date_mise_service',
        'alerte_meteo_config'
    ];

    protected $casts = [
        'date_installation' => 'date',
        'date_mise_service' => 'date',
        'puissance_totale' => 'decimal:2',
        'surface_totale' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'inclinaison' => 'decimal:2',
        'cout_installation' => 'decimal:2',
        'alerte_meteo_config' => 'json'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function panneaux()
    {
        return $this->hasMany(Panneau::class);
    }

    public function onduleurs()
    {
        return $this->hasMany(Onduleur::class);
    }

    public function systemesStockage()
    {
        return $this->hasMany(SystemeStockage::class);
    }

    public function donneesProduction()
    {
        return $this->hasMany(DonneeProduction::class);
    }

    public function donneesConsommation()
    {
        return $this->hasMany(DonneeConsommation::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function alertes()
    {
        return $this->hasMany(Alerte::class);
    }

    public function recommandations()
    {
        return $this->hasMany(Recommandation::class);
    }

    protected static function getDescription(string $action, $model): string
    {
        switch ($action) {
            case 'création':
                return "Nouvelle installation créée : {$model->nom}";
            case 'modification':
                return "Modification de l'installation {$model->nom}";
            case 'suppression':
                return "Suppression de l'installation {$model->nom}";
            default:
                return parent::getDescription($action, $model);
        }
    }

    public function routeNotificationForMail()
    {
        return $this->utilisateur->email;
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}

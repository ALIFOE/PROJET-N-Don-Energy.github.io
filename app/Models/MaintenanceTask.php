<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceTask extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'installation_id',
        'type',
        'description',
        'date',
        'statut',
        'priorite',
        'notes',
        'date_prevue',
        'date_realisation'
    ];

    protected $casts = [
        'date' => 'date',
        'date_prevue' => 'date',
        'date_realisation' => 'date'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function installation()
    {
        return $this->belongsTo(Installation::class);
    }

    protected static function getDescription(string $action, $model): string
    {
        switch ($action) {
            case 'création':
                return "Nouvelle tâche de maintenance créée de type {$model->type}";
            case 'modification':
                return "Mise à jour de la tâche de maintenance n°{$model->id}";
            case 'suppression':
                return "Suppression de la tâche de maintenance n°{$model->id}";
            default:
                return parent::getDescription($action, $model);
        }
    }
}
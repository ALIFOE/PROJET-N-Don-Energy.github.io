<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Service;

class DemandeService extends Model
{
    use HasFactory;

    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_EN_COURS = 'en_cours';
    const STATUT_ACCEPTE = 'accepte';
    const STATUT_REFUSE = 'refuse';

    protected $table = 'demandes_services';

    protected $fillable = [
        'user_id',
        'service_id',
        'details',
        'statut'
    ];

    /**
     * Obtenir le libellé du statut en français
     */
    public function getStatutFr()
    {
        return match($this->statut) {
            self::STATUT_EN_ATTENTE => 'En attente',
            self::STATUT_EN_COURS => 'En cours',
            self::STATUT_ACCEPTE => 'Accepté',
            self::STATUT_REFUSE => 'Refusé',
            default => 'Inconnu'
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'table',
        'id_table',
        'description',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Obtenir l'utilisateur associé à cette activité
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

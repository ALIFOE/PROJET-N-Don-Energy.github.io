<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class FormationInscription extends Model
{
    use LogsActivity;

    protected $fillable = [
        'formation_id',
        'user_id',
        'nom',
        'email',
        'telephone',
        'acte_naissance_path',
        'cni_path',
        'diplome_path',
        'autres_documents_paths',
        'statut'
    ];

    protected $casts = [
        'autres_documents_paths' => 'array'
    ];

    // Une inscription appartient à une formation
    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    // Une inscription appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

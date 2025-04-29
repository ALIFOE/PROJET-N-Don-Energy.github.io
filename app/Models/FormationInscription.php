<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormationInscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'formation_id',
        'user_id',
        'nom',
        'acte_naissance_path',
        'cni_path',
        'diplome_path',
        'autres_documents_paths',
        'statut'
    ];

    protected $casts = [
        'autres_documents_paths' => 'array'
    ];

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

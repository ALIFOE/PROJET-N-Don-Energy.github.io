<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Functionality extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'icone',
        'statut'
    ];

    protected $casts = [
        'statut' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('statut', true);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activite extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id', // Ajout de user_id pour permettre son insertion
        'description',
        'created_at',
    ];
}

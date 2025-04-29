<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'prix',
        'image',
        'categorie',
        'en_stock'
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'en_stock' => 'boolean'
    ];
} 
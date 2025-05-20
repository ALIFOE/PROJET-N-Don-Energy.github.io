<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'path',
        'type',
        'is_featured'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];
}

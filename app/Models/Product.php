<?php

namespace App\Models;

use App\Traits\HasCurrency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, HasCurrency;

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

    protected $appends = ['formatted_price'];

    public function getFormattedPriceAttribute()
    {
        return self::formatAmount($this->prix);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
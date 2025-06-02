<?php

namespace App\Models;

use App\Traits\HasCurrency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, HasCurrency;

    const STATUS_EN_ATTENTE = 'en_attente';
    const STATUS_EN_COURS = 'en_cours';
    const STATUS_TERMINE = 'termine';
    const STATUS_ANNULE = 'annule';

    protected $fillable = [
        'product_id',
        'user_id',
        'quantity',
        'total_price',
        'status',
        'payment_method',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'hidden',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    protected $appends = ['formatted_total_price'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->status) {
                $order->status = self::STATUS_EN_ATTENTE;
            }
        });
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_EN_ATTENTE => 'En attente',
            self::STATUS_EN_COURS => 'En cours',
            self::STATUS_TERMINE => 'Terminé',
            self::STATUS_ANNULE => 'Annulé',
            default => ucfirst($this->status)
        };
    }

    public function getFormattedTotalPriceAttribute()
    {
        return self::formatAmount($this->total_price);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
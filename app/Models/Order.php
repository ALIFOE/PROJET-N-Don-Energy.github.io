<?php

namespace App\Models;

use App\Traits\HasCurrency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, HasCurrency;

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
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    protected $appends = ['formatted_total_price'];

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
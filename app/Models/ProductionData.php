<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionData extends Model
{
    use HasFactory;

    protected $fillable = [
        'installation_id',
        'timestamp',
        'current_power',
        'daily_energy',
        'total_energy',
        'temperature',
        'irradiance',
        'efficiency',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'current_power' => 'float',
        'daily_energy' => 'float',
        'total_energy' => 'float',
        'temperature' => 'float',
        'irradiance' => 'float',
        'efficiency' => 'float',
    ];

    public function installation()
    {
        return $this->belongsTo(Installation::class);
    }
} 
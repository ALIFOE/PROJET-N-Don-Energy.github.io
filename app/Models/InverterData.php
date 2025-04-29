<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InverterData extends Model
{
    use HasFactory;

    protected $fillable = [
        'inverter_id',
        'power',
        'voltage',
        'current',
        'frequency',
        'temperature',
        'daily_energy',
        'total_energy',
        'status'
    ];

    protected $casts = [
        'power' => 'float',
        'voltage' => 'float',
        'current' => 'float',
        'frequency' => 'float',
        'temperature' => 'float',
        'daily_energy' => 'float',
        'total_energy' => 'float'
    ];

    public function inverter()
    {
        return $this->belongsTo(Inverter::class);
    }
} 
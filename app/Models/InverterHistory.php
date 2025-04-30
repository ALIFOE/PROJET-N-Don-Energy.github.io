<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InverterHistory extends Model
{
    protected $fillable = [
        'inverter_name',
        'timestamp',
        'power',
        'energy',
        'voltage_dc',
        'current_dc',
        'voltage_ac',
        'current_ac',
        'frequency',
        'temperature',
        'efficiency'
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'power' => 'float',
        'energy' => 'float',
        'voltage_dc' => 'float',
        'current_dc' => 'float',
        'voltage_ac' => 'float',
        'current_ac' => 'float',
        'frequency' => 'float',
        'temperature' => 'float',
        'efficiency' => 'float'
    ];
}

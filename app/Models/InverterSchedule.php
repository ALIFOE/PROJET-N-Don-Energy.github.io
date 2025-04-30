<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InverterSchedule extends Model
{
    protected $fillable = [
        'inverter_name',
        'start_time',
        'end_time',
        'power_limit',
        'days'
    ];

    protected $casts = [
        'power_limit' => 'float',
        'days' => 'array'
    ];
}

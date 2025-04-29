<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inverter extends Model
{
    use HasFactory;

    protected $fillable = [
        'installation_id',
        'brand',
        'model',
        'serial_number',
        'ip_address',
        'port',
        'username',
        'password',
        'driver',
        'status',
        'connection_type',
        'connection_config'
    ];

    protected $casts = [
        'connection_config' => 'array',
        'last_connected_at' => 'datetime',
        'last_error' => 'array'
    ];

    public function installation()
    {
        return $this->belongsTo(Installation::class);
    }

    public function readings()
    {
        return $this->hasMany(InverterReading::class);
    }

    public function data()
    {
        return $this->hasMany(InverterData::class);
    }
}
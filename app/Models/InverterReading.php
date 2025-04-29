<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InverterReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'inverter_id',
        'reading_type',
        'data',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime'
    ];

    public function inverter()
    {
        return $this->belongsTo(Inverter::class);
    }

    public static function getLatestReadings(int $inverterId, string $readingType = 'realtime', int $limit = 100)
    {
        return static::where('inverter_id', $inverterId)
            ->where('reading_type', $readingType)
            ->orderBy('read_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getReadingsByDateRange(int $inverterId, string $startDate, string $endDate, string $readingType = 'realtime')
    {
        return static::where('inverter_id', $inverterId)
            ->where('reading_type', $readingType)
            ->whereBetween('read_at', [$startDate, $endDate])
            ->orderBy('read_at')
            ->get();
    }

    public function getValue(string $key)
    {
        return $this->data[$key] ?? null;
    }
}
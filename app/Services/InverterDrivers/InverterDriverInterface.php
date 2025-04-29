<?php

namespace App\Services\InverterDrivers;

interface InverterDriverInterface
{
    public function testConnection(): bool;
    public function getData(): array;
    public function getStatus(): string;
    public function getSettings(): array;
    public function updateSettings(array $settings): bool;
} 
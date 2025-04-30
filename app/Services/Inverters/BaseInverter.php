<?php

namespace App\Services\Inverters;

abstract class BaseInverter implements InverterInterface
{
    protected $config;
    protected $connected = false;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function isConnected(): bool
    {
        return $this->connected;
    }

    abstract protected function validateConfig(): bool;

    protected function handleError(\Exception $e): void
    {
        \Log::error('Erreur onduleur: ' . $e->getMessage(), [
            'type' => get_class($this),
            'config' => $this->config,
            'trace' => $e->getTraceAsString()
        ]);
        throw $e;
    }
}

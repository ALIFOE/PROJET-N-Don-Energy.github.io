<?php

namespace App\Services\InverterConnectors;

use App\Models\Inverter;
use App\Models\InverterReading;
use Exception;
use Illuminate\Support\Facades\Log;

abstract class BaseInverterConnector implements InverterConnectorInterface
{
    protected $inverter;
    protected $connected = false;
    protected $config;
    protected $logger;

    public function __construct(Inverter $inverter)
    {
        $this->inverter = $inverter;
        $this->config = $this->inverter->connection_config;
        $this->logger = Log::channel('inverters');
    }

    public function isConnected(): bool
    {
        return $this->connected;
    }

    protected function saveReading(array $data): void
    {
        try {
            InverterReading::create([
                'inverter_id' => $this->inverter->id,
                'reading_type' => 'realtime',
                'data' => json_encode($data),
                'read_at' => now(),
            ]);
        } catch (Exception $e) {
            $this->logger->error("Erreur lors de l'enregistrement des données: " . $e->getMessage());
        }
    }
}
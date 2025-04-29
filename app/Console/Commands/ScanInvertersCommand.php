<?php

namespace App\Console\Commands;

use App\Models\Installation;
use App\Services\InverterConnectors\InverterConnectorFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScanInvertersCommand extends Command
{
    protected $signature = 'inverters:scan {installation?} {--timeout=1}';
    protected $description = 'Recherche des onduleurs sur le réseau local';

    public function handle()
    {
        $installationId = $this->argument('installation');
        $timeout = $this->option('timeout');

        if ($installationId) {
            $installations = Installation::where('id', $installationId)->get();
        } else {
            $installations = Installation::all();
        }

        foreach ($installations as $installation) {
            $this->info("Scan du réseau pour l'installation #{$installation->id}...");
            $this->scanNetwork($installation, $timeout);
        }
    }

    protected function scanNetwork(Installation $installation, int $timeout)
    {
        $network = $this->getLocalNetwork();
        $portsToScan = [502, 1502, 8080, 80]; // Ports communs pour Modbus TCP et REST API

        foreach ($network as $ip) {
            foreach ($portsToScan as $port) {
                if ($this->isPortOpen($ip, $port, $timeout)) {
                    $this->info("Port ouvert trouvé: {$ip}:{$port}");
                    
                    try {
                        // Essaie de se connecter avec différents protocoles
                        foreach (InverterConnectorFactory::getAvailableConnectors() as $type) {
                            $config = [
                                'host' => $ip,
                                'port' => $port,
                                'timeout' => $timeout
                            ];
                            
                            if ($this->testConnection($type, $config)) {
                                $this->info("Onduleur trouvé sur {$ip}:{$port} avec le protocole {$type}");
                                
                                // Récupère les informations de l'onduleur
                                $deviceInfo = $this->getDeviceInfo($type, $config);
                                if ($deviceInfo) {
                                    $this->createInverter($installation, $deviceInfo, $type, $config);
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        Log::debug("Erreur lors du test de {$ip}:{$port} - " . $e->getMessage());
                    }
                }
            }
        }
    }

    protected function getLocalNetwork(): array
    {
        $ip = $_SERVER['SERVER_ADDR'] ?? '192.168.1.1';
        $subnet = substr($ip, 0, strrpos($ip, '.') + 1);
        
        $network = [];
        for ($i = 1; $i < 255; $i++) {
            $network[] = $subnet . $i;
        }
        
        return $network;
    }

    protected function isPortOpen(string $ip, int $port, int $timeout): bool
    {
        $connection = @fsockopen($ip, $port, $errno, $errstr, $timeout);
        if ($connection) {
            fclose($connection);
            return true;
        }
        return false;
    }

    protected function testConnection(string $type, array $config): bool
    {
        try {
            // Crée un onduleur temporaire pour tester la connexion
            $inverter = new \App\Models\Inverter([
                'connection_type' => $type,
                'connection_config' => $config,
                'ip_address' => $config['host'],
                'port' => $config['port']
            ]);

            $connector = InverterConnectorFactory::create($inverter);
            return $connector->connect();
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function getDeviceInfo(string $type, array $config): ?array
    {
        try {
            $inverter = new \App\Models\Inverter([
                'connection_type' => $type,
                'connection_config' => $config,
                'ip_address' => $config['host'],
                'port' => $config['port']
            ]);

            $connector = InverterConnectorFactory::create($inverter);
            if ($connector->connect()) {
                return $connector->getDeviceInfo();
            }
        } catch (\Exception $e) {
            Log::error("Erreur lors de la récupération des infos: " . $e->getMessage());
        }
        return null;
    }

    protected function createInverter(Installation $installation, array $deviceInfo, string $type, array $config)
    {
        try {
            $inverter = \App\Models\Inverter::create([
                'installation_id' => $installation->id,
                'brand' => $deviceInfo['manufacturer'] ?? 'Unknown',
                'model' => $deviceInfo['model'] ?? 'Unknown',
                'serial_number' => $deviceInfo['serial'] ?? null,
                'ip_address' => $config['host'],
                'port' => $config['port'],
                'connection_type' => $type,
                'connection_config' => $config,
                'status' => 'discovered'
            ]);

            $this->info("Onduleur enregistré: ID #{$inverter->id}");
        } catch (\Exception $e) {
            $this->error("Erreur lors de l'enregistrement de l'onduleur: " . $e->getMessage());
        }
    }
}
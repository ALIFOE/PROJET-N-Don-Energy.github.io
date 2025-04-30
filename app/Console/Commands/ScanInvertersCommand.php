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
        try {
            // Log du début du scan réseau
            Log::info("Début du scan réseau pour l'installation #{$installation->id}");

            $network = $this->getLocalNetwork();
            if (empty($network)) {
                Log::error("Impossible de déterminer le réseau local");
                throw new \Exception("Impossible de déterminer le réseau local");
            }

            Log::info("Réseau local détecté : {$network}");

            // Scan des ports courants pour les onduleurs
            $ports = [502, 1502, 80, 8080]; // Ports communs pour Modbus TCP et API Web
            
            foreach ($ports as $port) {
                $this->info("Scan du port {$port}...");
                
                try {
                    $socket = @fsockopen($network, $port, $errno, $errstr, $timeout);
                    
                    if ($socket) {
                        $address = "{$network}:{$port}";
                        Log::info("Port ouvert trouvé: {$address}");
                        $this->info("Port ouvert trouvé: {$address}");
                        
                        // Tenter de détecter le protocole et l'onduleur
                        $protocol = $this->detectProtocol($network, $port);
                        
                        if ($protocol) {
                            $this->info("Onduleur trouvé sur {$address} avec le protocole {$protocol}");
                            Log::info("Onduleur trouvé sur {$address} avec le protocole {$protocol}");
                            
                            // Enregistrer l'onduleur dans la base de données
                            $inverter = $this->registerInverter($installation, $network, $port, $protocol);
                            if ($inverter) {
                                $this->info("Onduleur enregistré: ID #{$inverter->id}");
                            }
                        }
                        
                        fclose($socket);
                    }
                } catch (\Exception $e) {
                    Log::warning("Erreur lors du scan du port {$port}: " . $e->getMessage());
                    continue; // Continuer avec le port suivant
                }
            }
            
        } catch (\Exception $e) {
            Log::error("Erreur lors du scan réseau: " . $e->getMessage());
            throw $e;
        }
    }

    protected function getLocalNetwork()
    {
        // Obtenir l'adresse IP locale
        $localIP = gethostbyname(gethostname());
        if ($localIP === false || $localIP === gethostname()) {
            Log::error("Impossible d'obtenir l'adresse IP locale");
            return null;
        }

        // Extraire le préfixe réseau (supposons un masque /24)
        $parts = explode('.', $localIP);
        if (count($parts) !== 4) {
            Log::error("Format d'adresse IP invalide: {$localIP}");
            return null;
        }

        // Retourner le préfixe réseau
        return "{$parts[0]}.{$parts[1]}.{$parts[2]}.1";
    }

    protected function detectProtocol($ip, $port)
    {
        try {
            // Tester Modbus TCP
            if ($port == 502 || $port == 1502) {
                $socket = @fsockopen($ip, $port, $errno, $errstr, 1);
                if ($socket) {
                    // En-tête Modbus TCP minimal
                    $data = pack("n*", 0, 0, 6, 1, 3, 0);
                    fwrite($socket, $data);
                    $response = fread($socket, 2);
                    fclose($socket);
                    
                    if ($response !== false && strlen($response) > 0) {
                        return 'modbus_tcp';
                    }
                }
            }

            // Tester l'API Web
            if ($port == 80 || $port == 8080) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "http://{$ip}:{$port}/api/info");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 2);
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode == 200) {
                    return 'rest_api';
                }
            }

        } catch (\Exception $e) {
            Log::warning("Erreur lors de la détection du protocole: " . $e->getMessage());
        }

        return null;
    }

    protected function registerInverter(Installation $installation, $ip, $port, $protocol)
    {
        try {
            $inverter = new \App\Models\Inverter([
                'installation_id' => $installation->id,
                'connection_config' => [
                    'host' => $ip,
                    'port' => $port,
                    'protocol' => $protocol
                ],
                'status' => 'discovered'
            ]);
            
            $inverter->save();
            return $inverter;
            
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'enregistrement de l'onduleur: " . $e->getMessage());
            return null;
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Inverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InverterController extends Controller
{
    public function scanNetwork(Request $request)
    {
        try {
            $inverters = [];
            $network = $this->getLocalNetwork();
            
            foreach ($network as $ip) {
                // Test des ports communs pour les onduleurs
                $ports = [502, 503, 504, 505]; // Ports Modbus TCP communs
                
                foreach ($ports as $port) {
                    if ($this->isPortOpen($ip, $port)) {
                        try {
                            // Tentative de connexion et lecture des informations
                            $deviceInfo = $this->readDeviceInfo($ip, $port);
                            
                            if ($deviceInfo) {
                                $inverters[] = [
                                    'ip' => $ip,
                                    'port' => $port,
                                    'brand' => $deviceInfo['brand'],
                                    'model' => $deviceInfo['model'],
                                    'serial_number' => $deviceInfo['serial_number']
                                ];
                            }
                        } catch (\Exception $e) {
                            Log::debug("Erreur de connexion à {$ip}:{$port} - " . $e->getMessage());
                            continue;
                        }
                    }
                }
            }
            
            return response()->json($inverters);
            
        } catch (\Exception $e) {
            Log::error("Erreur lors du scan du réseau: " . $e->getMessage());
            return response()->json(['error' => 'Erreur lors du scan du réseau'], 500);
        }
    }

    public function connect(Request $request)
    {
        try {
            $validated = $request->validate([
                'brand' => 'required|string',
                'model' => 'required|string',
                'ip_address' => 'required|ip',
                'port' => 'required|integer',
                'username' => 'required|string',
                'password' => 'required|string'
            ]);

            $inverter = new Inverter();
            $inverter->brand = $validated['brand'];
            $inverter->model = $validated['model'];
            $inverter->ip_address = $validated['ip_address'];
            $inverter->port = $validated['port'];
            $inverter->username = $validated['username'];
            $inverter->password = $validated['password'];
            $inverter->status = 'connecting';
            $inverter->user_id = auth()->id();
            $inverter->save();

            // Tester la connexion
            if ($this->testConnection($inverter)) {
                $inverter->status = 'connected';
                $inverter->save();
                return response()->json(['success' => true, 'message' => 'Onduleur connecté avec succès']);
            } else {
                $inverter->delete();
                return response()->json(['success' => false, 'message' => 'Impossible de se connecter à l\'onduleur'], 400);
            }

        } catch (\Exception $e) {
            Log::error("Erreur lors de la connexion de l'onduleur: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erreur lors de la connexion'], 500);
        }
    }

    private function getLocalNetwork()
    {
        $network = [];
        $ip = $_SERVER['SERVER_ADDR'];
        $subnet = substr($ip, 0, strrpos($ip, '.') + 1);
        
        // Scanner les 255 adresses IP possibles
        for ($i = 1; $i < 255; $i++) {
            $network[] = $subnet . $i;
        }
        
        return $network;
    }

    private function isPortOpen($ip, $port)
    {
        $connection = @fsockopen($ip, $port, $errno, $errstr, 1);
        if ($connection) {
            fclose($connection);
            return true;
        }
        return false;
    }

    private function readDeviceInfo($ip, $port)
    {
        try {
            $socket = @fsockopen($ip, $port, $errno, $errstr, 1);
            if (!$socket) {
                return null;
            }

            // Envoi d'une requête Modbus TCP simple pour lire les informations de l'appareil
            $request = $this->createModbusRequest(0x01, 0x03, 0x0000, 0x000A);
            fwrite($socket, $request);
            
            // Lecture de la réponse
            $response = fread($socket, 1024);
            fclose($socket);

            if (strlen($response) > 0) {
                // Analyse de la réponse pour identifier la marque et le modèle
                return $this->parseDeviceInfo($response);
            }

            return null;
        } catch (\Exception $e) {
            Log::debug("Erreur lors de la lecture des informations: " . $e->getMessage());
            return null;
        }
    }

    private function createModbusRequest($unitId, $functionCode, $startAddress, $quantity)
    {
        $transactionId = rand(0, 65535);
        $length = 6;
        
        $request = pack('n*', 
            $transactionId,    // Transaction ID
            0x0000,           // Protocol ID
            $length,          // Length
            $unitId,          // Unit ID
            $functionCode,    // Function Code
            $startAddress,    // Start Address
            $quantity         // Quantity
        );
        
        return $request;
    }

    private function parseDeviceInfo($response)
    {
        // Analyse de la réponse pour identifier la marque et le modèle
        // Cette partie doit être adaptée selon les protocoles spécifiques des différents fabricants
        $data = unpack('n*', $response);
        
        // Exemple de logique d'identification basée sur les données reçues
        $deviceInfo = [
            'brand' => $this->identifyBrand($data),
            'model' => $this->identifyModel($data),
            'serial_number' => $this->extractSerialNumber($data)
        ];
        
        return $deviceInfo;
    }

    private function identifyBrand($data)
    {
        // Logique d'identification de la marque basée sur les données lues
        // À adapter selon les protocoles spécifiques
        return 'sma'; // Exemple
    }

    private function identifyModel($data)
    {
        // Logique d'identification du modèle basée sur les données lues
        // À adapter selon les protocoles spécifiques
        return 'sunny-boy-5.0'; // Exemple
    }

    private function extractSerialNumber($data)
    {
        // Logique d'extraction du numéro de série
        // À adapter selon les protocoles spécifiques
        return implode('', array_map('chr', $data));
    }

    private function testConnection($inverter)
    {
        try {
            $socket = @fsockopen($inverter->ip_address, $inverter->port, $errno, $errstr, 1);
            if (!$socket) {
                return false;
            }

            // Envoi d'une requête de test simple
            $request = $this->createModbusRequest(0x01, 0x03, 0x0000, 0x0001);
            fwrite($socket, $request);
            
            // Lecture de la réponse
            $response = fread($socket, 1024);
            fclose($socket);

            return strlen($response) > 0;
        } catch (\Exception $e) {
            Log::error("Erreur de test de connexion: " . $e->getMessage());
            return false;
        }
    }
} 
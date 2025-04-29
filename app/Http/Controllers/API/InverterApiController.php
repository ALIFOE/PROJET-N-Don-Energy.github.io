<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\InverterConnectors\InverterConnectorFactory;
use App\Models\Inverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InverterApiController extends Controller
{
    public function scan()
    {
        try {
            // Exécuter la commande de scan
            \Artisan::call('inverters:scan', [
                '--timeout' => 2
            ]);

            // Récupérer les résultats
            $output = \Artisan::output();
            Log::info("Résultat du scan: " . $output);

            // Parser les résultats pour les renvoyer en JSON
            preg_match_all('/Port ouvert trouvé: (.*?)\n.*?Onduleur trouvé.*?avec le protocole (.*?)\n.*?Onduleur enregistré: ID #(\d+)/s', 
                $output, $matches, PREG_SET_ORDER);

            $results = [];
            foreach ($matches as $match) {
                $address = explode(':', $match[1]);
                $inverter = Inverter::find($match[3]);
                if ($inverter) {
                    $results[] = [
                        'id' => $inverter->id,
                        'brand' => $inverter->brand,
                        'model' => $inverter->model,
                        'serial_number' => $inverter->serial_number,
                        'ip' => $address[0],
                        'port' => $address[1],
                        'connection_type' => $match[2]
                    ];
                }
            }

            return response()->json($results);
        } catch (\Exception $e) {
            Log::error("Erreur lors du scan des onduleurs: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function testConnection(Request $request)
    {
        try {
            $validated = $request->validate([
                'brand' => 'required|string',
                'model' => 'required|string',
                'ip_address' => 'required|string',
                'port' => 'required|integer',
                'connection_type' => 'required|string',
                'username' => 'nullable|string',
                'password' => 'nullable|string',
                'unit_id' => 'nullable|integer',
                'api_key' => 'nullable|string',
                'timeout' => 'nullable|integer'
            ]);

            // Créer un onduleur temporaire pour tester la connexion
            $inverter = new Inverter($validated);
            $inverter->connection_config = array_filter([
                'host' => $validated['ip_address'],
                'port' => $validated['port'],
                'username' => $validated['username'] ?? null,
                'password' => $validated['password'] ?? null,
                'unit_id' => $validated['unit_id'] ?? 1,
                'api_key' => $validated['api_key'] ?? null,
                'timeout' => $validated['timeout'] ?? 5
            ]);

            // Tenter la connexion
            $connector = InverterConnectorFactory::create($inverter);
            $success = $connector->connect();

            if ($success) {
                // Tenter de récupérer les informations de l'appareil
                $deviceInfo = $connector->getDeviceInfo();
                $connector->disconnect();

                return response()->json([
                    'success' => true,
                    'message' => 'Connexion réussie',
                    'device_info' => $deviceInfo
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => "Impossible d'établir la connexion"
            ], 400);

        } catch (\Exception $e) {
            Log::error("Erreur lors du test de connexion: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getModels(string $brand)
    {
        Log::info("Récupération des modèles pour la marque: " . $brand);
        $config = config('inverters.brands');
        
        if (isset($config[$brand])) {
            Log::info("Modèles trouvés pour " . $brand . ": " . json_encode($config[$brand]['models'] ?? []));
            return response()->json([
                'success' => true,
                'models' => $config[$brand]['models'] ?? []
            ]);
        }
        
        Log::warning("Marque non trouvée: " . $brand);
        return response()->json([
            'success' => false,
            'message' => 'Marque non trouvée'
        ], 404);
    }
}
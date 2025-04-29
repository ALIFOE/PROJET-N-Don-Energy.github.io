<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Installation;
use App\Models\Inverter;
use App\Models\InverterData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InverterController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $installation = Installation::where('utilisateur_id', $user->id)->first();
        $inverters = Inverter::where('installation_id', $installation->id)->get();
        
        return view('client.inverters.index', compact('inverters', 'installation'));
    }

    public function connect(Request $request)
    {
        $request->validate([
            'brand' => 'required|string',
            'model' => 'required|string',
            'ip_address' => 'required|ip',
            'port' => 'required|integer',
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = Auth::user();
        $installation = Installation::where('utilisateur_id', $user->id)->first();

        if (!$installation) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune installation trouvée pour cet utilisateur'
            ], 404);
        }

        try {
            $inverter = new Inverter([
                'installation_id' => $installation->id,
                'brand' => $request->brand,
                'model' => $request->model,
                'ip_address' => $request->ip_address,
                'port' => $request->port,
                'username' => $request->username,
                'password' => $request->password,
                'status' => 'connecting'
            ]);
            $inverter->save();

            // Tenter la connexion à l'onduleur
            $connectionStatus = $this->testConnection($inverter);
            
            if ($connectionStatus['success']) {
                $inverter->status = 'connected';
                $inverter->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Connexion établie avec succès',
                    'inverter' => $inverter
                ]);
            } else {
                $inverter->delete();
                return response()->json([
                    'success' => false,
                    'message' => 'Échec de la connexion: ' . $connectionStatus['error']
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Erreur de connexion à l\'onduleur: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la connexion'
            ], 500);
        }
    }

    public function disconnect($id)
    {
        $inverter = Inverter::findOrFail($id);
        $inverter->status = 'disconnected';
        $inverter->save();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie'
        ]);
    }

    private function testConnection($inverter)
    {
        // Liste des drivers d'onduleurs supportés
        $drivers = [
            'sma' => 'SMA',
            'solaredge' => 'SolarEdge',
            'fronius' => 'Fronius',
            'goodwe' => 'GoodWe',
            'huawei' => 'Huawei',
            'solax' => 'Solax',
            'growatt' => 'Growatt',
            'victron' => 'Victron'
        ];

        try {
            // Tester la connexion avec chaque driver jusqu'à trouver celui qui fonctionne
            foreach ($drivers as $driver => $name) {
                $driverClass = "App\\Services\\InverterDrivers\\" . ucfirst($driver) . "Driver";
                if (class_exists($driverClass)) {
                    $driverInstance = new $driverClass($inverter);
                    if ($driverInstance->testConnection()) {
                        $inverter->driver = $driver;
                        return ['success' => true];
                    }
                }
            }

            return [
                'success' => false,
                'error' => 'Aucun driver compatible trouvé pour cet onduleur'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function getData($id)
    {
        $inverter = Inverter::findOrFail($id);
        $driver = $inverter->getDriverInstance();

        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Driver non trouvé pour cet onduleur'
            ], 404);
        }

        try {
            $data = $driver->getData();
            
            // Sauvegarder les données
            $inverterData = new InverterData([
                'inverter_id' => $inverter->id,
                'power' => $data['power'] ?? null,
                'voltage' => $data['voltage'] ?? null,
                'current' => $data['current'] ?? null,
                'frequency' => $data['frequency'] ?? null,
                'temperature' => $data['temperature'] ?? null,
                'daily_energy' => $data['daily_energy'] ?? null,
                'total_energy' => $data['total_energy'] ?? null,
                'status' => $data['status'] ?? null
            ]);
            $inverterData->save();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des données: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSettings($id)
    {
        $inverter = Inverter::findOrFail($id);
        $driver = $inverter->getDriverInstance();

        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Driver non trouvé pour cet onduleur'
            ], 404);
        }

        try {
            $settings = $driver->getSettings();
            return response()->json([
                'success' => true,
                'settings' => $settings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des paramètres: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateSettings(Request $request, $id)
    {
        $inverter = Inverter::findOrFail($id);
        $driver = $inverter->getDriverInstance();

        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Driver non trouvé pour cet onduleur'
            ], 404);
        }

        try {
            $success = $driver->updateSettings($request->all());
            return response()->json([
                'success' => $success,
                'message' => $success ? 'Paramètres mis à jour avec succès' : 'Échec de la mise à jour des paramètres'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour des paramètres: ' . $e->getMessage()
            ], 500);
        }
    }

    public function realtimeData()
    {
        $user = Auth::user();
        $installation = Installation::where('utilisateur_id', $user->id)->first();

        if (!$installation) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune installation trouvée pour cet utilisateur.'
            ], 404);
        }

        $inverterData = InverterData::where('installation_id', $installation->id)
            ->orderBy('created_at', 'desc')
            ->take(10) // Dernières 10 entrées
            ->get();

        return response()->json([
            'success' => true,
            'data' => $inverterData
        ]);
    }
}
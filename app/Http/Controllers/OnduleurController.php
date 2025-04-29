<?php

namespace App\Http\Controllers;

use App\Models\Onduleur;
use App\Models\InverterData;
use Illuminate\Http\Request;

class OnduleurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $onduleurs = Onduleur::orderBy('created_at', 'desc')->get();
        return view('onduleurs.index', compact('onduleurs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('onduleurs.create'); // Assurez-vous que cette vue existe
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'marque' => 'required|string',
            'modele' => 'required|string|max:255',
            'numero_serie' => 'required|string|max:255|unique:onduleurs',
            'ip_address' => 'required|ip',
            'port' => 'required|integer|min:1|max:65535',
            'protocole' => 'required|string',
            'est_connecte' => 'boolean'
        ]);

        try {
            $onduleur = Onduleur::create([
                'user_id' => $validatedData['user_id'],
                'modele' => $validatedData['modele'],
                'marque' => $validatedData['marque'],
                'numero_serie' => $validatedData['numero_serie'],
                'est_connecte' => false
            ]);

            // Si la case "Connecter l'onduleur" est cochée
            if ($request->input('est_connecte')) {
                // Tester la connexion
                $inverter = new \App\Models\Inverter([
                    'ip_address' => $validatedData['ip_address'],
                    'port' => $validatedData['port'],
                    'connection_type' => $validatedData['protocole']
                ]);

                $connector = \App\Services\InverterConnectors\InverterConnectorFactory::create($inverter);
                
                if ($connector->connect()) {
                    $onduleur->est_connecte = true;
                    $onduleur->save();
                    
                    // Rediriger vers la page pui-production si la connexion réussit
                    return redirect()->route('pui-production')
                        ->with('success', 'Onduleur ajouté et connecté avec succès');
                }
            }

            // Si pas de connexion demandée ou si la connexion a échoué
            return redirect()->route('onduleurs.index')
                ->with('success', 'Onduleur ajouté avec succès');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Erreur lors de l'ajout de l'onduleur: " . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', "Une erreur est survenue lors de l'ajout de l'onduleur");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Onduleur  $onduleur
     * @return \Illuminate\Http\Response
     */
    public function show(Onduleur $onduleur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Onduleur  $onduleur
     * @return \Illuminate\Http\Response
     */
    public function edit(Onduleur $onduleur)
    {
        return view('onduleurs.edit', compact('onduleur'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Onduleur  $onduleur
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Onduleur $onduleur)
    {
        $validatedData = $request->validate([
            'modele' => 'required|string|max:255',
            'numero_serie' => 'required|string|max:255|unique:onduleurs,numero_serie,' . $onduleur->id,
            'est_connecte' => 'required|boolean'
        ]);

        $onduleur->update($validatedData);

        return redirect()->route('onduleurs.index')
            ->with('success', 'Onduleur mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Onduleur  $onduleur
     * @return \Illuminate\Http\Response
     */
    public function destroy(Onduleur $onduleur)
    {
        $onduleur->delete();

        return redirect()->route('onduleurs.index')
            ->with('success', 'Onduleur supprimé avec succès');
    }

    /**
     * Basculer l'état de connexion de l'onduleur
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleConnection($id)
    {
        $onduleur = Onduleur::findOrFail($id);
        $onduleur->est_connecte = !$onduleur->est_connecte;
        $onduleur->save();

        $status = $onduleur->est_connecte ? 'connecté' : 'déconnecté';
        return back()->with('success', "L'onduleur a été $status avec succès");
    }

    /**
     * Afficher les performances détaillées d'un onduleur
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function performance($id)
    {
        $onduleur = Onduleur::findOrFail($id);
        
        // Récupérer les données de performance des dernières 24 heures
        $donnees = InverterData::where('inverter_id', $onduleur->id)
            ->where('created_at', '>=', now()->subHours(24))
            ->orderBy('created_at')
            ->get();

        return view('onduleurs.performance', compact('onduleur', 'donnees'));
    }

    /**
     * Récupérer les dernières données de l'onduleur
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getLatestData($id)
    {
        $onduleur = Onduleur::findOrFail($id);
        
        $latestData = InverterData::where('inverter_id', $onduleur->id)
            ->latest()
            ->first();

        if (!$latestData) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune donnée disponible'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'power' => $latestData->power,
                'daily_energy' => $latestData->daily_energy,
                'total_energy' => $latestData->total_energy,
                'temperature' => $latestData->temperature,
                'efficiency' => $latestData->efficiency,
                'voltage' => $latestData->voltage,
                'current' => $latestData->current,
                'frequency' => $latestData->frequency,
                'status' => $latestData->status,
                'timestamp' => $latestData->created_at
            ]
        ]);
    }

    /**
     * Teste la connexion à un onduleur
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function testConnection(Request $request)
    {
        try {
            $validated = $request->validate([
                'ip' => 'required|ip',
                'port' => 'required|integer|min:1|max:65535',
                'protocol' => 'required|string|in:modbus_tcp,sunspec,rest_api'
            ]);

            // Vérifier d'abord si le port est accessible
            $connection = @fsockopen($validated['ip'], $validated['port'], $errno, $errstr, 3);
            
            if (!$connection) {
                // Erreur spécifique pour le pare-feu
                if ($errno == 10060 || $errno == 10061) {
                    return response()->json([
                        'success' => false,
                        'message' => "Le pare-feu bloque la connexion. Vérifiez que le port {$validated['port']} est ouvert.",
                        'error_code' => $errno
                    ]);
                }
                return response()->json([
                    'success' => false,
                    'message' => "Impossible d'établir la connexion: $errstr",
                    'error_code' => $errno
                ]);
            }
            fclose($connection);

            // Créer un onduleur temporaire pour tester
            $inverter = new \App\Models\Inverter([
                'ip_address' => $validated['ip'],
                'port' => $validated['port'],
                'connection_type' => $validated['protocol'],
                'connection_config' => [
                    'host' => $validated['ip'],
                    'port' => $validated['port'],
                    'timeout' => 5
                ]
            ]);

            // Tenter la connexion avec le protocole spécifique
            $connector = \App\Services\InverterConnectors\InverterConnectorFactory::create($inverter);
            $success = $connector->connect();

            if ($success) {
                $connector->disconnect();
                return response()->json([
                    'success' => true,
                    'message' => 'Connexion réussie'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => "La connexion a échoué. Vérifiez les paramètres et que le pare-feu autorise les connexions."
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Erreur lors du test de connexion: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Erreur de connexion: " . $e->getMessage()
            ], 500);
        }
    }
}

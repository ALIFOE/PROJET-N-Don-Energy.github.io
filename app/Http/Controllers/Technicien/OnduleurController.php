<?php

namespace App\Http\Controllers\Technicien;

use App\Http\Controllers\Controller;
use App\Models\Onduleur;
use App\Models\Installation;
use App\Services\InverterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Models\Activity;
use function Spatie\Activitylog\activity;

class OnduleurController extends Controller
{
    protected $inverterService;

    public function __construct(InverterService $inverterService)
    {
        $this->inverterService = $inverterService;
    }

    public function index()
    {
        $onduleurs = Onduleur::with('installation')
            ->whereHas('installation', function($query) {
                $query->where('technicien_id', auth()->id());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('technicien.onduleurs.index', compact('onduleurs'));
    }

    public function show(Onduleur $onduleur)
    {
        $this->authorize('view', $onduleur);
        return view('technicien.onduleurs.show', compact('onduleur'));
    }

    public function edit(Onduleur $onduleur)
    {
        $this->authorize('update', $onduleur);
        return view('technicien.onduleurs.edit', compact('onduleur'));
    }

    public function checkConnection(Onduleur $onduleur)
    {
        try {
            // Vérifier d'abord si l'onduleur est connecté
            if (!$onduleur->est_connecte) {
                return response()->json([
                    'connected' => false,
                    'message' => 'Onduleur non connecté'
                ]);
            }

            $connected = $this->inverterService->checkConnection($onduleur);
            
            if (!$connected) {
                // Mettre à jour l'état de l'onduleur en base de données
                $onduleur->est_connecte = false;
                $onduleur->save();
                
                return response()->json([
                    'connected' => false,
                    'message' => 'La connexion a été perdue'
                ]);
            }

            try {
                $metrics = $this->inverterService->getCurrentMetrics($onduleur);
                
                return response()->json([
                    'connected' => true,
                    'metrics' => $metrics
                ]);
            } catch (\Exception $e) {
                Log::warning("Erreur lors de la récupération des métriques: " . $e->getMessage());
                
                return response()->json([
                    'connected' => true,
                    'metrics' => null,
                    'message' => 'Connecté mais impossible de lire les métriques'
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Erreur lors de la vérification de la connexion: " . $e->getMessage());
            return response()->json([
                'connected' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function testConnection(Onduleur $onduleur)
    {
        try {
            $result = $this->inverterService->testConnection($onduleur);
            return response()->json([
                'success' => $result,
                'message' => $result ? 'Connexion réussie' : 'Échec de la connexion'
            ]);
        } catch (\Exception $e) {
            Log::error("Erreur lors du test de connexion: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function resetConnection(Onduleur $onduleur)
    {
        try {
            $this->inverterService->resetConnection($onduleur);
            return response()->json([
                'success' => true,
                'message' => 'Connexion réinitialisée avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error("Erreur lors de la réinitialisation: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Onduleur $onduleur)
    {
        try {
            $this->authorize('delete', $onduleur);
            $onduleur->delete();
            
            activity()
                ->performedOn($onduleur)
                ->causedBy(auth()->user())
                ->withProperties([
                    'installation' => $onduleur->installation->nom,
                    'modele' => $onduleur->modele,
                    'numero_serie' => $onduleur->numero_serie
                ])
                ->log('suppression');

            return response()->json([
                'success' => true,
                'message' => 'Onduleur supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error("Erreur lors de la suppression: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

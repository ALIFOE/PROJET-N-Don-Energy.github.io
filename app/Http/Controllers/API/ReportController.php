<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ReportController extends Controller
{
    public function download(string $type, string $period): JsonResponse
    {
        try {
            // Simuler la génération d'un rapport
            $filename = "rapport-{$period}-" . date('Y-m-d') . ".{$type}";
            $content = "Ceci est un exemple de rapport {$type} pour la période {$period}";
            
            // Dans un cas réel, vous généreriez ici le PDF ou Excel avec les vraies données
            Storage::put("rapports/{$filename}", $content);
            
            // Renvoyer le chemin du fichier
            return response()->json([
                'success' => true,
                'file_url' => Storage::url("rapports/{$filename}")
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function savePreferences(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'frequency' => 'required|string|in:quotidien,hebdomadaire,mensuel',
                'formats' => 'required|array|min:1',
                'formats.*' => 'string|in:pdf,excel,csv'
            ]);

            User::where('id', auth()->id())->update([
                'report_frequency' => $validated['frequency'],
                'report_formats' => $validated['formats']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Préférences sauvegardées avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
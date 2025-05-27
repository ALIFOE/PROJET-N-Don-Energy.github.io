<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Exports\ProductionExport;
use Carbon\Carbon;

class RapportController extends Controller
{
    public function index()
    {
        // Pour l'instant, nous retournons simplement la vue
        // Plus tard, nous ajouterons la logique pour générer les rapports
        return view('rapports-analyses');
    }

    public function exportPDF(Request $request)
    {
        $period = $request->input('period', 'mensuel');
        $data = $this->getProductionData($period);
        
        $pdf = PDF::loadView('pdf.rapport-production', [
            'data' => $data,
            'period' => $period,
            'date' => now()->format('d/m/Y')
        ]);
        
        return $pdf->download('rapport-production-' . $period . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $period = $request->input('period', 'mensuel');
        $exporter = new ProductionExport($period);
        $filePath = $exporter->generateCsv();
        
        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    private function getProductionData($period)
    {
        // Simuler les données de production
        $data = [
            'production_totale' => rand(1000, 5000),
            'efficacite' => rand(85, 95),
            'economie_co2' => rand(500, 2000),
            'economie_xof' => rand(100000, 500000),
        ];

        return $data;
    }
}
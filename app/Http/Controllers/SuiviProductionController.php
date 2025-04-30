<?php

namespace App\Http\Controllers;

use App\Models\Onduleur;
use App\Services\InverterDataService;
use Illuminate\Http\Request;

class SuiviProductionController extends Controller
{
    protected $inverterService;

    public function __construct(InverterDataService $inverterService)
    {
        $this->inverterService = $inverterService;
    }

    public function index()
    {
        return view('suivi-production');
    }

    public function getData(Request $request)
    {
        $period = $request->input('periode', '24h');
        
        $currentData = $this->inverterService->getCurrentData();
        $productionData = $this->inverterService->getProductionData($period);
        $performanceData = $this->inverterService->getPerformanceData();

        return response()->json([
            'current' => $currentData,
            'production' => $productionData,
            'performance' => $performanceData
        ]);
    }

    public function exportPdf(Request $request)
    {
        $period = $request->input('periode', '24h');
        $data = $this->inverterService->getProductionData($period);
        
        // Logique d'export PDF
        $pdfPath = storage_path('exports/production_data.pdf'); // Define the PDF file path
        // Generate the PDF file and save it to the defined path
        // Example: Use a PDF generation library like DomPDF or TCPDF
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.production', ['data' => $data]);
        $pdf->save($pdfPath);

        return response()->download($pdfPath);
    }

    public function exportCsv(Request $request)
    {
        $period = $request->input('periode', '24h');
        $data = $this->inverterService->getProductionData($period);
        
        // Logique d'export CSV
        $csvPath = storage_path('exports/production_data.csv'); // Define the CSV file path
        // Generate the CSV file and save it to the defined path
        $file = fopen($csvPath, 'w');
        fputcsv($file, ['Column1', 'Column2', 'Column3']); // Example headers
        foreach ($data as $row) {
            fputcsv($file, $row); // Write data rows
        }
        fclose($file);

        return response()->download($csvPath);
    }
}

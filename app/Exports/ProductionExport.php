<?php

namespace App\Exports;

use Carbon\Carbon;

class ProductionExport
{
    private $period;

    public function __construct($period)
    {
        $this->period = $period;
    }

    public function generateCsv()
    {
        $data = $this->getData();
        $headers = [
            'Date',
            'Production (kWh)',
            'Efficacité (%)',
            'Température (°C)',
            'Irradiance (W/m²)',
            'Économies (XOF)',
        ];

        $filename = storage_path('app/public/exports/production_' . date('Y-m-d_His') . '.csv');
        $directory = dirname($filename);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $handle = fopen($filename, 'w');
        fputcsv($handle, $headers);

        foreach ($data as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);
        return $filename;
    }

    private function getData(): array
    {
        $data = [];
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now();
        
        for ($date = clone $startDate; $date <= $endDate; $date->addDay()) {
            $data[] = [
                $date->format('Y-m-d'),
                rand(50, 200), // Production en kWh
                rand(85, 95), // Efficacité en %
                rand(20, 40), // Température en °C
                rand(500, 2000), // Irradiance en W/m²
                rand(5000, 15000), // Économies en XOF
            ];
        }
        
        return $data;
    }
}

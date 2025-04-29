<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Suivi de Production</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .info {
            margin-bottom: 20px;
        }
        .chart-container {
            width: 100%;
            margin: 20px 0;
            page-break-inside: avoid;
        }
        
        .chart {
            width: 100%;
            height: 300px;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 30px;
        }
        
        .chart-title {
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .summary-stats {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        
        .stat-box {
            background: #f8f9fa;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            width: 30%;
            margin-bottom: 10px;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .stat-label {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport de Suivi de Production</h1>
        <p>Période : {{ $startDate->format('d/m/Y H:i') }} - {{ $endDate->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info">
        <h2>Informations de l'Installation</h2>
        <p><strong>Installation ID:</strong> {{ $installation->id }}</p>
        <p><strong>Puissance installée:</strong> {{ $installation->puissance_installee }} kWc</p>
        <p><strong>Date d'installation:</strong> {{ $installation->date_installation->format('d/m/Y') }}</p>
    </div>

    <div class="summary-stats">
        <div class="stat-box">
            <div class="stat-value">{{ number_format($data->avg('power'), 2) }}</div>
            <div class="stat-label">Production moyenne (kW)</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ number_format($data->max('power'), 2) }}</div>
            <div class="stat-label">Production max (kW)</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ number_format($data->avg('temperature'), 1) }}</div>
            <div class="stat-label">Température moyenne (°C)</div>
        </div>
    </div>

    <div class="chart-container">
        <div class="chart-title">Courbe de Production (kW)</div>
        <div class="chart">
            @php
                $chartData = $data->map(function($item) {
                    return [
                        'time' => is_string($item['timestamp']) ? $item['timestamp'] : $item['timestamp']->format('H:i'),
                        'power' => $item['power']
                    ];
                });
                
                // Créer les points pour le graphique
                $maxY = $data->max('power');
                $height = 250; // hauteur du graphique en pixels
                $width = 800;  // largeur du graphique en pixels
                $padding = 40; // padding pour les axes
                
                $points = [];
                foreach($chartData as $i => $point) {
                    $x = $padding + ($i * (($width - 2 * $padding) / (count($chartData) - 1)));
                    $y = $height - $padding - ($point['power'] * ($height - 2 * $padding) / $maxY);
                    $points[] = "$x,$y";
                }
                
                $pathData = "M " . implode(" L ", $points);
            @endphp
            
            <svg width="100%" height="100%" viewBox="0 0 800 300">
                <!-- Axe Y -->
                <line x1="{{ $padding }}" y1="{{ $padding }}" x2="{{ $padding }}" y2="{{ $height - $padding }}" 
                      stroke="black" stroke-width="1"/>
                
                <!-- Axe X -->
                <line x1="{{ $padding }}" y1="{{ $height - $padding }}" x2="{{ $width - $padding }}" y2="{{ $height - $padding }}" 
                      stroke="black" stroke-width="1"/>
                
                <!-- Courbe de production -->
                <path d="{{ $pathData }}" fill="none" stroke="#2196F3" stroke-width="2"/>
                
                <!-- Points de données -->
                @foreach($points as $i => $point)
                    @php
                        list($x, $y) = explode(',', $point);
                    @endphp
                    <circle cx="{{ $x }}" cy="{{ $y }}" r="3" fill="#2196F3"/>
                @endforeach
                
                <!-- Labels axe Y -->
                @for($i = 0; $i <= 5; $i++)
                    @php
                        $labelY = $height - $padding - ($i * ($height - 2 * $padding) / 5);
                        $valueY = number_format($maxY * $i / 5, 1);
                    @endphp
                    <text x="{{ $padding - 5 }}" y="{{ $labelY }}" text-anchor="end" alignment-baseline="middle" 
                          font-size="10">{{ $valueY }}</text>
                @endfor
                
                <!-- Labels axe X -->
                @foreach($chartData as $i => $point)
                    @if($i % 4 == 0)
                        @php
                            $x = $padding + ($i * (($width - 2 * $padding) / (count($chartData) - 1)));
                        @endphp
                        <text x="{{ $x }}" y="{{ $height - $padding + 15 }}" text-anchor="middle" 
                              font-size="10">{{ $point['time'] }}</text>
                    @endif
                @endforeach
            </svg>
        </div>
    </div>

    <div class="chart-container">
        <div class="chart-title">Température et Irradiance</div>
        <div class="chart">
            @php
                $maxTemp = $data->max('temperature');
                $maxIrr = $data->max('irradiance');
                
                $tempPoints = [];
                $irrPoints = [];
                foreach($data as $i => $item) {
                    $x = $padding + ($i * (($width - 2 * $padding) / (count($data) - 1)));
                    
                    // Points pour la température
                    $yTemp = $height - $padding - ($item['temperature'] * ($height - 2 * $padding) / $maxTemp);
                    $tempPoints[] = "$x,$yTemp";
                    
                    // Points pour l'irradiance
                    $yIrr = $height - $padding - ($item['irradiance'] * ($height - 2 * $padding) / $maxIrr);
                    $irrPoints[] = "$x,$yIrr";
                }
                
                $tempPathData = "M " . implode(" L ", $tempPoints);
                $irrPathData = "M " . implode(" L ", $irrPoints);
            @endphp
            
            <svg width="100%" height="100%" viewBox="0 0 800 300">
                <!-- Axes -->
                <line x1="{{ $padding }}" y1="{{ $padding }}" x2="{{ $padding }}" y2="{{ $height - $padding }}" 
                      stroke="black" stroke-width="1"/>
                <line x1="{{ $padding }}" y1="{{ $height - $padding }}" x2="{{ $width - $padding }}" y2="{{ $height - $padding }}" 
                      stroke="black" stroke-width="1"/>
                
                <!-- Courbes -->
                <path d="{{ $tempPathData }}" fill="none" stroke="#FF5722" stroke-width="2"/>
                <path d="{{ $irrPathData }}" fill="none" stroke="#FFC107" stroke-width="2"/>
                
                <!-- Légende -->
                <rect x="{{ $width - 150 }}" y="10" width="12" height="12" fill="#FF5722"/>
                <text x="{{ $width - 130 }}" y="20" font-size="12">Température</text>
                <rect x="{{ $width - 150 }}" y="30" width="12" height="12" fill="#FFC107"/>
                <text x="{{ $width - 130 }}" y="40" font-size="12">Irradiance</text>
                
                <!-- Labels axe Y gauche (Température) -->
                @for($i = 0; $i <= 5; $i++)
                    @php
                        $labelY = $height - $padding - ($i * ($height - 2 * $padding) / 5);
                        $valueY = number_format($maxTemp * $i / 5, 1);
                    @endphp
                    <text x="{{ $padding - 5 }}" y="{{ $labelY }}" text-anchor="end" alignment-baseline="middle" 
                          font-size="10">{{ $valueY }}°C</text>
                @endfor
                
                <!-- Labels axe Y droite (Irradiance) -->
                @for($i = 0; $i <= 5; $i++)
                    @php
                        $labelY = $height - $padding - ($i * ($height - 2 * $padding) / 5);
                        $valueY = number_format($maxIrr * $i / 5, 0);
                    @endphp
                    <text x="{{ $width - $padding + 5 }}" y="{{ $labelY }}" text-anchor="start" alignment-baseline="middle" 
                          font-size="10">{{ $valueY }} W/m²</text>
                @endfor
                
                <!-- Labels axe X -->
                @foreach($data as $i => $item)
                    @if($i % 4 == 0)
                        @php
                            $x = $padding + ($i * (($width - 2 * $padding) / (count($data) - 1)));
                            $time = is_string($item['timestamp']) ? 
                                substr($item['timestamp'], -5) : 
                                $item['timestamp']->format('H:i');
                        @endphp
                        <text x="{{ $x }}" y="{{ $height - $padding + 15 }}" text-anchor="middle" 
                              font-size="10">{{ $time }}</text>
                    @endif
                @endforeach
            </svg>
        </div>
    </div>

    <h2>Données détaillées</h2>
    <table>
        <thead>
            <tr>
                <th>Date/Heure</th>
                <th>Puissance (kW)</th>
                <th>Irradiance (W/m²)</th>
                <th>Température (°C)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ is_string($item['timestamp']) ? $item['timestamp'] : $item['timestamp']->format('d/m/Y H:i') }}</td>
                    <td>{{ number_format($item['power'], 2) }}</td>
                    <td>{{ number_format($item['irradiance'], 0) }}</td>
                    <td>{{ number_format($item['temperature'], 1) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

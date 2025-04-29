<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport de Production Solaire</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .stats-container { margin: 20px 0; }
        .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .stat-box { 
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .chart-container { margin: 20px 0; }
        table { 
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th { background-color: #f8f9fa; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport de Production Solaire</h1>
        <p>Installation : {{ $installation->nom }}</p>
        <p>Période : {{ \Carbon\Carbon::parse($periode['debut'])->format('d/m/Y H:i') }} - {{ \Carbon\Carbon::parse($periode['fin'])->format('d/m/Y H:i') }}</p>
    </div>

    <div class="stats-container">
        <h2>Statistiques de Production</h2>
        <div class="stats-grid">
            <div class="stat-box">
                <h3>Production Moyenne</h3>
                <p>{{ number_format($data['stats']['production_moyenne'], 2) }} kW</p>
            </div>
            <div class="stat-box">
                <h3>Production Maximale</h3>
                <p>{{ number_format($data['stats']['production_max'], 2) }} kW</p>
            </div>
            <div class="stat-box">
                <h3>Température Moyenne</h3>
                <p>{{ number_format($data['stats']['temperature_moyenne'], 1) }} °C</p>
            </div>
            <div class="stat-box">
                <h3>Irradiance Moyenne</h3>
                <p>{{ number_format($data['stats']['irradiance_moyenne'], 0) }} W/m²</p>
            </div>
        </div>
    </div>

    <div class="data-table">
        <h2>Données Détaillées</h2>
        <table>
            <thead>
                <tr>
                    <th>Horodatage</th>
                    <th>Production (kW)</th>
                    <th>Irradiance (W/m²)</th>
                    <th>Température (°C)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['production'] as $timestamp => $production)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($timestamp)->format('d/m/Y H:i') }}</td>
                    <td>{{ number_format($production, 2) }}</td>
                    <td>{{ number_format($data['irradiance'][$timestamp], 0) }}</td>
                    <td>{{ number_format($data['temperature'][$timestamp], 1) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Rapport généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rapport de Production</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 24px;
            color: #1a56db;
            margin-bottom: 10px;
        }
        .date {
            color: #666;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 18px;
            color: #1a56db;
            margin-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
        }
        .stat-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 24px;
            color: #1a56db;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo">
        <h1 class="title">Rapport de Production</h1>
        <div class="date">{{ $date }}</div>
    </div>

    <div class="section">
        <h2 class="section-title">Résumé de la production</h2>
        <div class="grid">
            <div class="stat-card">
                <div class="stat-label">Production totale</div>
                <div class="stat-value">{{ number_format($data['production_totale'], 0, ',', ' ') }} kWh</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Efficacité moyenne</div>
                <div class="stat-value">{{ $data['efficacite'] }}%</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">Impact environnemental et économique</h2>
        <div class="grid">
            <div class="stat-card">
                <div class="stat-label">Réduction CO2</div>
                <div class="stat-value">{{ number_format($data['economie_co2'], 0, ',', ' ') }} kg</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Économies réalisées</div>
                <div class="stat-value">{{ number_format($data['economie_xof'], 0, ',', ' ') }} XOF</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>N'CREFER - Rapport généré le {{ $date }}</p>
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __("Historique des activités") }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #1d4ed8;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 10px;
        }
        .company-info {
            margin-bottom: 20px;
            text-align: center;
            color: #1d4ed8;
            font-size: 14px;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        h1 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 30px;
            color: #1d4ed8;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #1d4ed8;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .date {
            width: 20%;
        }
        .action {
            width: 20%;
        }
        .description {
            width: 60%;
        }
        .footer {
            text-align: center;
            font-size: 11px;
            color: #666;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="N'CREFER Logo" class="logo">
        <div class="company-info">
            <div class="company-name">N'CREFER</div>
            <div>Expert en Solutions d'Énergie Solaire</div>
            <div>123 Avenue de l'Énergie Verte</div>
            <div>75000 Paris, France</div>
            <div>Tél: +228 97 73 43 81</div>
            <div>Email: contact@ndon-energy.com</div>
        </div>
    </div>

    <h1>{{ __("Historique des activités") }}</h1>

    <table>
        <thead>
            <tr>
                <th class="date">{{ __("Date") }}</th>
                <th class="action">{{ __("Action") }}</th>
                <th class="description">{{ __("Description") }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activites as $activite)
                <tr>
                    <td>{{ $activite->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ ucfirst($activite->action) }}</td>
                    <td>{{ $activite->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div>{{ __("Document généré le") }} {{ now()->format('d/m/Y H:i') }}</div>
        <div>N'CREFER - SIRET: 123 456 789 00001 - TVA: FR12 123456789</div>
        <div>www.ndon-energy.com</div>
    </div>
</body>
</html>
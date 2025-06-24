<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Devis #{{ $devis->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-width: 200px;
        }
        h1 {
            color: #2563eb;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            background-color: #f3f4f6;
            padding: 10px;
            margin-bottom: 10px;
            font-weight: bold;
            color: #1f2937;
        }
        .grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .grid-row {
            display: table-row;
        }
        .grid-cell {
            display: table-cell;
            padding: 5px;
        }
        .label {
            color: #6b7280;
            font-size: 0.9em;
            width: 30%;
        }
        .value {
            font-weight: bold;
            color: #1f2937;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
        }
        .status-success {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-error {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 0.8em;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Devis #{{ $devis->id }}</h1>
        <p>Généré le {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Informations Client</div>
        <div class="grid">
            <div class="grid-row">
                <div class="grid-cell label">Nom</div>
                <div class="grid-cell value">{{ $devis->nom }}</div>
            </div>
            <div class="grid-row">
                <div class="grid-cell label">Prénom</div>
                <div class="grid-cell value">{{ $devis->prenom }}</div>
            </div>
            <div class="grid-row">
                <div class="grid-cell label">Email</div>
                <div class="grid-cell value">{{ $devis->email }}</div>
            </div>
            <div class="grid-row">
                <div class="grid-cell label">Téléphone</div>
                <div class="grid-cell value">{{ $devis->telephone }}</div>
            </div>
            <div class="grid-row">
                <div class="grid-cell label">Adresse</div>
                <div class="grid-cell value">{{ $devis->adresse }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Détails Installation</div>
        <div class="grid">
            <div class="grid-row">
                <div class="grid-cell label">Type de Bâtiment</div>
                <div class="grid-cell value">{{ $devis->type_batiment }}</div>
            </div>
            <div class="grid-row">
                <div class="grid-cell label">Type de Toiture</div>
                <div class="grid-cell value">{{ $devis->type_toiture }}</div>
            </div>
            <div class="grid-row">
                <div class="grid-cell label">Orientation</div>
                <div class="grid-cell value">{{ $devis->orientation }}</div>
            </div>
            <div class="grid-row">
                <div class="grid-cell label">Consommation Annuelle</div>
                <div class="grid-cell value">{{ $devis->consommation_annuelle }} kWh</div>
            </div>
        </div>
    </div>

    @if(isset($devis->analyse_technique))
    <div class="section">
        <div class="section-title">Analyse Technique</div>
        <div class="grid">
            @if(isset($devis->analyse_technique['status']))
            <div class="grid-row">
                <div class="grid-cell label">Statut</div>
                <div class="grid-cell value">
                    <span class="status {{ $devis->analyse_technique['status'] === 'faisable' ? 'status-success' : 'status-error' }}">
                        {{ $devis->analyse_technique['status'] === 'faisable' ? 'Faisable' : 'Non Faisable' }}
                    </span>
                </div>
            </div>
            @endif

            @if(isset($devis->analyse_technique['dimensionnement']))
            <div class="grid-row">
                <div class="grid-cell label">Dimensionnement</div>
                <div class="grid-cell value">
                    @foreach($devis->analyse_technique['dimensionnement'] as $key => $value)
                        <div>{{ ucfirst(str_replace('_', ' ', $key)) }} : {{ is_array($value) ? implode(', ', $value) : $value }}</div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <div class="footer">
        <p>N'CREFER - Solutions d'énergie solaire</p>
        <p>Tel: +XXX XXX XXX - Email: contact@nCREFER.com</p>
    </div>
</body>
</html>

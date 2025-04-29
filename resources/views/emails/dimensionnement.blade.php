<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nouvelle demande de dimensionnement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #1e88e5;
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .label {
            font-weight: bold;
            color: #1e88e5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nouvelle demande de dimensionnement</h1>
        </div>

        <div class="section">
            <h2>Informations personnelles</h2>
            <p><span class="label">Nom :</span> {{ $data['nom'] }}</p>
            <p><span class="label">Email :</span> {{ $data['email'] }}</p>
            <p><span class="label">Téléphone :</span> {{ $data['telephone'] }}</p>
            <p><span class="label">Adresse :</span> {{ $data['adresse'] }}</p>
        </div>

        <div class="section">
            <h2>Informations sur la consommation</h2>
            @if(isset($data['facture_annuelle']))
                <p><span class="label">Facture annuelle :</span> {{ $data['facture_annuelle'] }} €</p>
            @endif
            @if(isset($data['fournisseur']))
                <p><span class="label">Fournisseur :</span> {{ $data['fournisseur'] }}</p>
            @endif
            @if(isset($data['nb_personnes']))
                <p><span class="label">Nombre de personnes dans le foyer :</span> {{ $data['nb_personnes'] }}</p>
            @endif
        </div>

        <div class="section">
            <h2>Informations sur le logement</h2>
            @if(isset($data['type_logement']))
                <p><span class="label">Type de logement :</span> {{ ucfirst($data['type_logement']) }}</p>
            @endif
            @if(isset($data['surface_toiture']))
                <p><span class="label">Surface de toiture :</span> {{ $data['surface_toiture'] }} m²</p>
            @endif
            @if(isset($data['orientation']))
                <p><span class="label">Orientation :</span> {{ ucfirst($data['orientation']) }}</p>
            @endif
            @if(isset($data['type_installation']))
                <p><span class="label">Type d'installation souhaité :</span> {{ ucfirst($data['type_installation']) }}</p>
            @endif
        </div>

        @if(isset($data['budget']))
            <div class="section">
                <h2>Budget</h2>
                <p><span class="label">Budget envisagé :</span> {{ $data['budget'] }} €</p>
            </div>
        @endif

        @if(isset($data['equipements']) && !empty($data['equipements']))
            <div class="section">
                <h2>Équipements existants</h2>
                <ul>
                    @foreach($data['equipements'] as $equipement)
                        <li>{{ ucfirst(str_replace('_', ' ', $equipement)) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(isset($data['objectifs']) && !empty($data['objectifs']))
            <div class="section">
                <h2>Objectifs</h2>
                <ul>
                    @foreach($data['objectifs'] as $objectif)
                        <li>{{ ucfirst(str_replace('_', ' ', $objectif)) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</body>
</html>
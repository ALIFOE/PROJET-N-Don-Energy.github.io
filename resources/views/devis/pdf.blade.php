<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Résultats de l'analyse - Don Energy</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { 
            border-bottom: 2px solid #1e88e5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1e88e5;
        }
        .contact-info {
            text-align: right;
            font-size: 14px;
        }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 18px; font-weight: bold; margin-bottom: 10px; color: #1e88e5; }
        .info-box { padding: 10px; margin-bottom: 10px; border: 1px solid #ddd; }
        .success { color: #0f5132; }
        .warning { color: #664d03; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            <div class="logo">
                <i class="fas fa-solar-panel"></i> Né Don Energy
            </div>
            <div class="contact-info">
                <p>Rue KOPEGA 56.GB<br>
                Lomé, Togo<br>
                Tél: +228 97 73 43 81<br>
                Email: contact@nedonenergy.com</p>
            </div>
        </div>
    </div>

    <h1>Résultats de l'analyse de votre projet solaire</h1>
    
    @if($analyseData && isset($analyseData['status']) && $analyseData['status'] === 'success')
        <!-- Faisabilité technique -->
        <div class="section">
            <div class="section-title">Faisabilité technique</div>
            <div class="info-box">
                <p class="success">Score de faisabilité : {{ number_format($analyseData['faisabilite']['score_faisabilite'] * 100, 0) }}%</p>
                <p>{{ $analyseData['faisabilite']['commentaires'] }}</p>
            </div>
        </div>

        <!-- Dimensionnement -->
        <div class="section">
            <div class="section-title">Dimensionnement recommandé</div>
            <div class="info-box">
                <h3>Installation</h3>
                <ul>
                    <li>Puissance totale : {{ $analyseData['dimensionnement']['puissance_kwc'] }} kWc</li>
                    <li>Type de panneau : {{ $analyseData['dimensionnement']['type_panneau'] }}</li>
                    <li>Fabricant : {{ $analyseData['dimensionnement']['fabricant'] }}</li>
                    <li>Modèle : {{ $analyseData['dimensionnement']['modele'] }}</li>
                    <li>Capacité par panneau : {{ $analyseData['dimensionnement']['capacite_panneau'] }} Wc</li>
                    <li>Rendement : {{ number_format($analyseData['dimensionnement']['rendement_panneau'] * 100, 1) }}%</li>
                    <li>Garantie : {{ $analyseData['dimensionnement']['garantie_annees'] }} ans</li>
                    <li>Nombre de panneaux : {{ $analyseData['dimensionnement']['nombre_panneaux'] }}</li>
                    <li>Surface nécessaire : {{ $analyseData['dimensionnement']['surface_necessaire'] }} m²</li>
                </ul>
            </div>
            <div class="info-box">
                <h3>Production estimée</h3>
                <p>{{ number_format($analyseData['dimensionnement']['production_estimee'], 0) }} kWh/an</p>
            </div>
        </div>

        <!-- Analyse financière -->
        <div class="section">
            <div class="section-title">Analyse financière</div>
            <div class="info-box">
                <h3>Investissement</h3>
                <ul>
                    <li>Coût estimé : {{ number_format($analyseData['analyse_financiere']['cout_installation'], 0) }} €</li>
                    <li>Économies annuelles : {{ number_format($analyseData['analyse_financiere']['economies_annuelles'], 0) }} €</li>
                    <li>Retour sur investissement : {{ $analyseData['analyse_financiere']['retour_investissement_annees'] }} ans</li>
                </ul>
            </div>
            <div class="info-box">
                <h3>Rentabilité à 20 ans</h3>
                <p>{{ number_format($analyseData['analyse_financiere']['rentabilite_20_ans'], 0) }} €</p>
            </div>
        </div>

        <!-- Recommandations -->
        @if(!empty($analyseData['recommandations']))
            <div class="section">
                <div class="section-title">Recommandations</div>
                <div class="info-box">
                    <ul>
                        @foreach($analyseData['recommandations'] as $recommandation)
                            <li>{{ $recommandation }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div style="margin-top: 20px">
            <p style="font-size: 12px; color: #666;">
                * Ces résultats sont basés sur des estimations et peuvent varier en fonction des conditions réelles d'installation et d'utilisation.
            </p>
        </div>
    @else
        <div class="info-box warning">
            <p>{{ $analyse['message'] ?? 'Une erreur est survenue lors de l\'analyse.' }}</p>
        </div>
    @endif
</body>
</html>
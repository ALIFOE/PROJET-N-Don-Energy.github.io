<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Résultats de l'analyse - CREFER</title>    <style>
        :root {
            --primary-color: #008037;
            --secondary-color: #4CAF50;
            --accent-color: #FFC107;
            --text-color: #333333;
            --background-light: #F5F5F5;
            --white: #FFFFFF;
        }
        
        body { 
            font-family: DejaVu Sans, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: var(--background-light);
        }
        
        .header { 
            border-bottom: 3px solid var(--primary-color);
            padding: 25px;
            margin-bottom: 40px;
            background: var(--white);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 12px;
        }
        
        .company-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: var(--primary-color);
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo i {
            color: var(--accent-color);
        }
        
        .contact-info {
            text-align: right;
            font-size: 14px;
            color: var(--text-color);
            line-height: 1.8;
            background-color: var(--background-light);
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
        }
        
        .section { 
            margin-bottom: 30px;
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 25px;
            border: 1px solid rgba(0,128,55,0.1);
        }
        
        .section-title { 
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
            color: var(--primary-color);
            border-bottom: 2px solid var(--secondary-color);
            padding-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .info-box { 
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(0,128,55,0.2);
            border-radius: 8px;
            background-color: var(--white);
            transition: all 0.3s ease;
        }
        
        .info-box:hover {
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .info-box h3 {
            color: var(--primary-color);
            margin-bottom: 15px;
            font-size: 18px;
            border-left: 4px solid var(--accent-color);
            padding-left: 10px;
        }
        
        .info-box ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }
        
        .info-box ul li {
            margin-bottom: 12px;
            padding-left: 25px;
            position: relative;
            color: var(--text-color);
        }
        
        .info-box ul li:before {
            content: "✓";
            color: var(--secondary-color);
            position: absolute;
            left: 0;
            font-weight: bold;
        }
        
        .success { 
            color: var(--primary-color);
            font-weight: bold;
            font-size: 1.3em;
            background: rgba(0,128,55,0.1);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        
        .warning { 
            color: #D32F2F;
            background-color: #FFEBEE;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #D32F2F;
            margin: 20px 0;
        }
        
        h1 {
            color: var(--primary-color);
            text-align: center;
            margin: 40px 0;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            padding-bottom: 15px;
        }
        
        h1:after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--accent-color);
        }
    </style>
</head>
<body>
    <div class="header" style="background:linear-gradient(90deg,#008037 0%,#4CAF50 100%);color:#fff;padding:30px 25px 20px 25px;border-radius:12px 12px 0 0;box-shadow:0 4px 6px rgba(0,0,0,0.08);margin-bottom:40px;">
        <div class="company-info" style="display:flex;justify-content:space-between;align-items:flex-start;">
            <div class="logo" style="font-size:32px;font-weight:bold;color:#FFC107;display:flex;align-items:center;gap:10px;">
                <span style="font-size:36px;">☀️</span> CREFER
            </div>
            <div class="contact-info" style="text-align:right;font-size:14px;line-height:1.8;background:rgba(255,255,255,0.12);padding:15px 20px;border-radius:8px;border-left:4px solid #FFC107;box-shadow:0 2px 8px #eee;">
                <p style="margin:0;">Lomé-TOGO,<br>Siège Social situé à Lomé-TOGO quartier Totsi Gblenkomé près de la salle de réunion des témoins de Jéhovah<br>Annexe au bord des pavés de Totsi non loin de l'agence TogoCom<br>Lomé, Togo<br>Tél: (+228)91 20 43 73 / (+228)92 53 14 55<br>Email: contact@crefer.com</p>
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
                    <li>Nombre de batteries recommandé : {{ $analyseData['dimensionnement']['nombre_batteries'] }}</li>
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
            <div class="info-box">                <h3>Investissement</h3>
                <ul>
                    <li>Coût estimé : <span style="color:#008037;font-weight:bold">{{ number_format($analyseData['analyse_financiere']['cout_installation'] * 655, 0, ',', ' ') }} FCFA</span></li>
                    <li>Économies annuelles : <span style="color:#008037;font-weight:bold">{{ number_format($analyseData['analyse_financiere']['economies_annuelles'] * 655, 0, ',', ' ') }} FCFA</span></li>
                    <li>Retour sur investissement : {{ $analyseData['analyse_financiere']['retour_investissement_annees'] }} ans</li>
                </ul>
            </div>
            <div class="info-box">                <h3>Rentabilité à 20 ans</h3>
                <p style="color:#008037;font-weight:bold">{{ number_format($analyseData['analyse_financiere']['rentabilite_20_ans'] * 655, 0, ',', ' ') }} FCFA</p>
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
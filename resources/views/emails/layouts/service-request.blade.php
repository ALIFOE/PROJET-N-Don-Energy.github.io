<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1a56db;
        }
        h1 {
            color: #111827;
            font-size: 24px;
            margin-bottom: 20px;
        }
        h2 {
            color: #374151;
            font-size: 18px;
            margin-top: 25px;
            margin-bottom: 15px;
        }
        p {
            color: #4b5563;
            margin-bottom: 15px;
        }
        ul {
            list-style-type: none;
            padding-left: 0;
        }
        li {
            color: #4b5563;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">CREFER</div>
        </div>

        <h1>Confirmation de votre demande de service</h1>

        <p>Bonjour {{ $serviceRequest->nom }},</p>

        <p>Nous avons bien reçu votre demande de service et nous vous en remercions.</p>

        <p>Notre équipe va étudier votre demande dans les plus brefs délais et vous recontactera pour vous fournir un devis détaillé.</p>

        <h2>Récapitulatif de votre demande :</h2>
        <ul>
            <li><strong>Service demandé :</strong> {{ $serviceRequest->service->nom }}</li>
            <li><strong>Description :</strong> {{ $serviceRequest->description }}</li>
            <li><strong>Date de la demande :</strong> {{ $serviceRequest->created_at->format('d/m/Y') }}</li>
        </ul>

        @if($serviceRequest->champs_specifiques && count($serviceRequest->champs_specifiques) > 0)
        <h2>Informations complémentaires :</h2>
        <ul>
            @foreach($serviceRequest->champs_specifiques as $champ => $valeur)
            <li><strong>{{ $champ }} :</strong> {{ $valeur }}</li>
            @endforeach
        </ul>
        @endif

        <p>Si vous avez des questions entre-temps, n'hésitez pas à nous contacter.</p>

        <p>Cordialement,<br>L'équipe CREFER</p>

        <div class="footer">
            © {{ date('Y') }} CREFER. Tous droits réservés.
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Confirmation de votre inscription</title>
</head>
<body>
    <h1>Bonjour {{ $details['nom'] }},</h1>
    <p>Merci de vous être inscrit à la formation : <strong>{{ $details['formation'] }}</strong>.</p>
    <p>Voici les détails de votre inscription :</p>
    <ul>
        <li><strong>Nom complet :</strong> {{ $details['nom'] }}</li>
        <li><strong>Adresse e-mail :</strong> {{ $details['email'] }}</li>
        <li><strong>Numéro de téléphone :</strong> {{ $details['telephone'] }}</li>
        @if (!empty($details['message']))
            <li><strong>Message ou questions :</strong> {{ $details['message'] }}</li>
        @endif
    </ul>
    <p>Pour finaliser votre inscription, veuillez compléter vos informations personnelles et téléverser les documents nécessaires en cliquant sur le lien ci-dessous :</p>
    <p style="text-align: center; margin: 20px 0;">
        <a href="{{ url('/finaliser-inscription/' . $details['id']) }}" style="background-color: #1e88e5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Finaliser mon inscription</a>
    </p>
    <p>Nous vous contacterons bientôt avec plus de détails concernant la formation.</p>
    <p>Cordialement,</p>
    <p>L'équipe D-CLIC</p>
</body>
</html>

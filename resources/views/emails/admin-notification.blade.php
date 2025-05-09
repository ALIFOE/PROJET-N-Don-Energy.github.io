<!DOCTYPE html>
<html>
<head>
    <title>Nouvelle inscription avec documents</title>
</head>
<body>
    <h1>Nouvelle inscription reçue</h1>
    <p>Voici les détails de l'inscription :</p>
    <ul>
        <li><strong>Nom complet :</strong> {{ $details['nom'] }}</li>
        <li><strong>Adresse e-mail :</strong> {{ $details['email'] }}</li>
        <li><strong>Numéro de téléphone :</strong> {{ $details['telephone'] }}</li>
        <li><strong>Formation choisie :</strong> {{ $details['formation'] }}</li>
        @if (!empty($details['message']))
            <li><strong>Message ou questions :</strong> {{ $details['message'] }}</li>
        @endif
        <li><strong>Document téléversé :</strong> <a href="{{ $details['document_url'] }}" target="_blank">Télécharger le document</a></li>
    </ul>
    <p>Cordialement,</p>
    <p>L'équipe CREFER</p>
</body>
</html>

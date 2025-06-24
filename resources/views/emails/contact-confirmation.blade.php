<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de réception de votre message</title>
</head>
<body style="font-family: Arial, sans-serif; color: #222;">
    <h2>Confirmation de réception de votre message</h2>
    <p>Bonjour {{ $contact['nom'] }},</p>
    <p>Nous avons bien reçu votre message et nous vous en remercions.</p>
    <p>Notre équipe traitera votre demande dans les plus brefs délais et vous répondra rapidement.</p>
    <h3>Récapitulatif de votre message :</h3>
    <ul>
        <li><strong>Sujet :</strong> {{ $contact['sujet'] }}</li>
        <li><strong>Date d'envoi :</strong> {{ now()->format('d/m/Y') }}</li>
    </ul>
    <p>Nous vous remercions de votre confiance.</p>
    <p>Cordialement,<br>L'équipe CREFER</p>
</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de votre demande de devis</title>
</head>
<body style="font-family: Arial, sans-serif; color: #222;">
    <h2>Confirmation de votre demande de devis</h2>
    <p>Bonjour {{ $serviceRequest->nom }},</p>
    <p>Nous avons bien reçu votre demande de devis et nous vous en remercions.</p>
    <p>Notre équipe va étudier votre demande dans les plus brefs délais et vous recontactera pour vous fournir un devis détaillé.</p>
    <h3>Récapitulatif de votre demande :</h3>
    <ul>
        <li><strong>Service demandé :</strong> {{ $serviceRequest->service->nom }}</li>
        <li><strong>Description :</strong> {{ $serviceRequest->description }}</li>
        <li><strong>Date de la demande :</strong> {{ $serviceRequest->created_at->format('d/m/Y') }}</li>
    </ul>
    @if($serviceRequest->champs_specifiques && count($serviceRequest->champs_specifiques) > 0)
        <h4>Informations complémentaires :</h4>
        <ul>
        @foreach($serviceRequest->champs_specifiques as $champ => $valeur)
            <li><strong>{{ $champ }}</strong> : {{ $valeur }}</li>
        @endforeach
        </ul>
    @endif
    <p>Si vous avez des questions entre-temps, n'hésitez pas à nous contacter.</p>
    <p>Cordialement,<br>L'équipe CREFER</p>
</body>
</html>

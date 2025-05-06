<!DOCTYPE html>
<html>
<head>
    <title>Nouvelle Inscription</title>
</head>
<body>
    <h1>Nouvelle Inscription à une Formation</h1>
    <p>Une nouvelle inscription a été effectuée. Voici les détails :</p>

    <ul>
        <li><strong>Nom :</strong> {{ $inscription->nom }}</li>
        <li><strong>Email :</strong> {{ $inscription->email }}</li>
        <li><strong>Téléphone :</strong> {{ $inscription->telephone }}</li>
        <li><strong>Formation :</strong> {{ $inscription->formation }}</li>
        <li><strong>Message :</strong> {{ $inscription->message }}</li>
    </ul>

    <p>Veuillez vérifier les informations et prendre les mesures nécessaires.</p>
</body>
</html>

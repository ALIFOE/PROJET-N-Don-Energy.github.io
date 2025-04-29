<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle demande de technicien</title>
</head>
<body>
    <h1>Nouvelle demande pour devenir technicien</h1>

    <p><strong>Nom :</strong> {{ $data['name'] }}</p>
    <p><strong>Email :</strong> {{ $data['email'] }}</p>
    <p><strong>Téléphone :</strong> {{ $data['phone'] }}</p>

    <p>Un CV a été soumis avec cette demande.</p>
</body>
</html>

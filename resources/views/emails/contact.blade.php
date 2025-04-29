<!DOCTYPE html>
<html>
<head>

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
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
        }
        .section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .section-title {
            color: #1e88e5;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>

    <meta charset="utf-8">
    <title>Nouveau message de contact</title>
</head>
<body>
    <h2>Nouveau message de contact</h2>
    
    <p><strong>Nom :</strong> {{ $contact['nom'] }}</p>
    <p><strong>Email :</strong> {{ $contact['email'] }}</p>
    @if(!empty($contact['telephone']))
        <p><strong>Téléphone :</strong> {{ $contact['telephone'] }}</p>
    @endif
    <p><strong>Sujet :</strong> {{ $contact['sujet'] }}</p>
    <p><strong>Message :</strong></p>
    <p>{{ $contact['message'] }}</p>
    
    <p>Date d'envoi : {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html> 
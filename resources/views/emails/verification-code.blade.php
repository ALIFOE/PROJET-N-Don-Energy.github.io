<!DOCTYPE html>
<html>
<head>
    <title>Code de vérification</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #f9f9f9; padding: 20px; border-radius: 5px;">
        <h2 style="color: #333;">Bonjour {{ $name }},</h2>
        
        <p>Merci de vous être inscrit sur N'Don Energy. Pour finaliser votre inscription, veuillez utiliser le code de vérification suivant :</p>
        
        <div style="background: #fff; padding: 15px; margin: 20px 0; text-align: center; border-radius: 5px;">
            <h1 style="color: #4a5568; letter-spacing: 5px; margin: 0;">{{ $code }}</h1>
        </div>
        
        <p>Ce code est valable pendant 30 minutes. Si vous n'avez pas demandé cette inscription, vous pouvez ignorer cet email.</p>
        
        <p style="color: #666; margin-top: 30px;">Cordialement,<br>L'équipe N'Don Energy</p>
    </div>
</body>
</html>

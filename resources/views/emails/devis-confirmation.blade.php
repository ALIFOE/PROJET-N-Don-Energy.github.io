<!-- Email confirmation de devis sans composant markdown Laravel -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de votre demande de devis</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #eee;">
        <tr>
            <td style="padding: 32px 32px 16px 32px;">
                <h2 style="color: #2563eb; margin-bottom: 24px;">Confirmation de votre demande de devis</h2>
                <p>Bonjour <strong>{{ $devis->nom }} {{ $devis->prenom }}</strong>,</p>
                <p>Nous avons bien reçu votre demande de devis et nous vous en remercions.</p>
                <h3 style="margin-top: 32px; margin-bottom: 12px; color: #111827;">Récapitulatif de votre demande :</h3>
                <ul style="margin-bottom: 24px;">
                    <li><strong>Type de bâtiment :</strong> {{ $devis->type_batiment }}</li>
                    <li><strong>Consommation annuelle :</strong> {{ $devis->consommation_annuelle }} kWh</li>
                    <li><strong>Type de toiture :</strong> {{ $devis->type_toiture }}</li>
                    <li><strong>Orientation :</strong> {{ $devis->orientation }}</li>
                </ul>
                <p>Notre équipe va étudier votre demande dans les plus brefs délais et vous recontactera avec un devis détaillé.</p>
                <p>Si vous avez des questions entre-temps, n'hésitez pas à nous contacter.</p>
                <p style="margin-top: 32px;">Cordialement,<br>L'équipe CREFER</p>
            </td>
        </tr>
    </table>
</body>
</html>

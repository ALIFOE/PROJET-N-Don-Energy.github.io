<x-mail::message>
# Confirmation de votre demande de devis

Bonjour {{ $devis->nom }} {{ $devis->prenom }},

Nous avons bien reçu votre demande de devis et nous vous en remercions.

## Récapitulatif de votre demande :
- Type de bâtiment : {{ $devis->type_batiment }}
- Consommation annuelle : {{ $devis->consommation_annuelle }} kWh
- Type de toiture : {{ $devis->type_toiture }}
- Orientation : {{ $devis->orientation }}

Notre équipe va étudier votre demande dans les plus brefs délais et vous recontactera avec un devis détaillé.

Si vous avez des questions entre-temps, n'hésitez pas à nous contacter.

Cordialement,
L'équipe CREFER

</x-mail::message>

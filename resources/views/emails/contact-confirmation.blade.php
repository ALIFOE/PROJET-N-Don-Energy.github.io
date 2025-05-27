<x-mail::message>
# Confirmation de réception de votre message

Bonjour {{ $contact['nom'] }},

Nous avons bien reçu votre message et nous vous en remercions.

Notre équipe traitera votre demande dans les plus brefs délais et vous répondra rapidement.

## Récapitulatif de votre message :
- Sujet : {{ $contact['sujet'] }}
- Date d'envoi : {{ now()->format('d/m/Y') }}

Nous vous remercions de votre confiance.

Cordialement,
L'équipe CREFER

</x-mail::message>

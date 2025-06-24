<x-mail::message>
# Confirmation d'inscription à la formation

Bonjour {{ $inscription->nom }},

Nous avons bien reçu votre demande d'inscription à la formation suivante :
**{{ $inscription->formation->titre }}**

## Détails de la formation :
- Date de début : {{ $inscription->formation->date_debut->format('d/m/Y') }}
- Durée : {{ $inscription->formation->duree }}
- Lieu : {{ $inscription->formation->lieu }}

Votre demande d'inscription est actuellement en cours d'examen par notre équipe. Nous vous contacterons prochainement pour vous informer de la suite du processus.

## Documents reçus :
- Acte de naissance
- Carte nationale d'identité
- Diplôme
{{ count($inscription->autres_documents_paths ?? []) > 0 ? '- ' . count($inscription->autres_documents_paths) . ' document(s) supplémentaire(s)' : '' }}

Vous pouvez suivre l'état de votre inscription dans votre espace personnel.

Si vous avez des questions entre-temps, n'hésitez pas à nous contacter.

Cordialement,<br>
L'équipe Né CREFER

</x-mail::message>

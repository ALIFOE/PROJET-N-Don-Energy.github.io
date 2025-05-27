@component('mail::message')
# Confirmation de votre demande de devis

Bonjour {{ $serviceRequest->nom }},

Nous avons bien reçu votre demande de devis et nous vous en remercions.

Notre équipe va étudier votre demande dans les plus brefs délais et vous recontactera pour vous fournir un devis détaillé.

## Récapitulatif de votre demande :
- Service demandé : {{ $serviceRequest->service->nom }}
- Description : {{ $serviceRequest->description }}
- Date de la demande : {{ $serviceRequest->created_at->format('d/m/Y') }}

@if($serviceRequest->champs_specifiques && count($serviceRequest->champs_specifiques) > 0)
## Informations complémentaires :
@foreach($serviceRequest->champs_specifiques as $champ => $valeur)
- **{{ $champ }}** : {{ $valeur }}
@endforeach
@endif

Si vous avez des questions entre-temps, n'hésitez pas à nous contacter.

Cordialement,<br>
L'équipe CREFER
@endcomponent

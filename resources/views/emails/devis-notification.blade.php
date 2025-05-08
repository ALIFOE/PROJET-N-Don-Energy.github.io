@component('mail::message')
# Nouveau devis d'installation

Un nouveau devis d'installation a été soumis par {{ $devis->nom }} {{ $devis->prenom }}.

## Détails du client
- **Email:** {{ $devis->email }}
- **Téléphone:** {{ $devis->telephone }}
- **Adresse:** {{ $devis->adresse }}

## Détails de l'installation
- **Type de bâtiment:** {{ $devis->type_batiment }}
- **Consommation annuelle:** {{ $devis->consommation_annuelle }} kWh
- **Type de toiture:** {{ $devis->type_toiture }}
- **Orientation:** {{ $devis->orientation }}

@if($devis->message)
## Message du client
{{ $devis->message }}
@endif

@component('mail::button', ['url' => route('admin.devis.show', $devis->id)])
Voir le devis
@endcomponent

Cordialement,<br>
{{ config('app.name') }}
@endcomponent
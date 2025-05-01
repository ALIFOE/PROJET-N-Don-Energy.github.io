@component('mail::message')
# Nouvelle inscription à une formation

Une nouvelle inscription a été enregistrée pour la formation **{{ $inscription->formation->nom }}**.

## Informations de l'inscrit :
- **Nom :** {{ $inscription->nom }}
- **Email :** {{ $inscription->email }}
- **Téléphone :** {{ $inscription->telephone }}
- **Date d'inscription :** {{ $inscription->created_at->format('d/m/Y H:i') }}

@component('mail::button', ['url' => route('admin.formations.inscriptions.show', $inscription)])
Voir les détails de l'inscription
@endcomponent

Cordialement,<br>
{{ config('app.name') }}
@endcomponent

@component('mail::message')
# Confirmation de votre demande de dimensionnement

Cher(e) {{ $dimensionnement->nom }},

Nous avons bien reçu votre demande de dimensionnement pour une installation photovoltaïque. Voici un récapitulatif de votre demande :

**Informations personnelles :**
- Adresse : {{ $dimensionnement->adresse }}
- Ville : {{ $dimensionnement->ville }}
- Code postal : {{ $dimensionnement->code_postal }}
- Téléphone : {{ $dimensionnement->telephone }}

**Informations techniques :**
- Type de logement : {{ $dimensionnement->type_logement }}
- Surface de toiture disponible : {{ $dimensionnement->surface_toiture }} m²
- Orientation : {{ $dimensionnement->orientation }}
- Type d'installation : {{ $dimensionnement->type_installation }}

**Consommation :**
- Facture annuelle : {{ number_format($dimensionnement->facture_annuelle, 2) }} €
- Nombre de personnes : {{ $dimensionnement->nb_personnes }}
- Fournisseur actuel : {{ $dimensionnement->fournisseur }}

Notre équipe technique va étudier votre demande et vous contactera dans les plus brefs délais pour discuter des solutions adaptées à vos besoins.

@component('mail::button', ['url' => route('dimensionnements.show', $dimensionnement->id)])
Voir ma demande
@endcomponent

Merci de votre confiance,<br>
{{ config('app.name') }}
@endcomponent
@component('mail::message')
# Confirmation de votre demande de dimensionnement

Cher(e) {{ $dimensionnement->nom }},

Nous avons bien reçu votre demande de dimensionnement pour une installation photovoltaïque. Voici un récapitulatif de votre demande :

**Informations personnelles :**
- Nom : {{ $dimensionnement->nom }}
- Email : {{ $dimensionnement->email }}
- Téléphone : {{ $dimensionnement->telephone }}
- Adresse : {{ $dimensionnement->adresse }}

**Caractéristiques du projet :**
- Type de logement : {{ ucfirst($dimensionnement->type_logement) }}
- Surface disponible : {{ number_format($dimensionnement->surface_toiture, 2) }} m²
- Orientation : {{ ucfirst($dimensionnement->orientation) }}
- Type d'installation souhaité : {{ ucfirst($dimensionnement->type_installation) }}
- Budget envisagé : {{ number_format($dimensionnement->budget, 2) }} €

**Consommation actuelle :**
- Facture annuelle : {{ number_format($dimensionnement->facture_annuelle, 2) }} €
- Nombre de personnes : {{ $dimensionnement->nb_personnes }}
- Fournisseur actuel : {{ $dimensionnement->fournisseur }}

**Équipements :**
@foreach($dimensionnement->equipements as $equipement)
- {{ ucfirst($equipement) }}
@endforeach

**Objectifs du projet :**
@foreach($dimensionnement->objectifs as $objectif)
- {{ ucfirst($objectif) }}
@endforeach

Notre équipe technique va étudier votre demande et vous contactera dans les plus brefs délais pour discuter des solutions adaptées à vos besoins.

@component('mail::button', ['url' => route('dimensionnements.show', $dimensionnement->id)])
Voir ma demande
@endcomponent

Merci de votre confiance,<br>
{{ config('app.name') }}
@endcomponent
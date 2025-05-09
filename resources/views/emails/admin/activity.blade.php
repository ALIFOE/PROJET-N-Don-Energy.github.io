@component('mail::message')
# Nouvelle Activité sur {{ config('app.name') }}

@if($activityType === 'order_placed')
**Nouvelle Commande**

Client : {{ $activityData['name'] }}  
Email : {{ $activityData['email'] }}  
Montant : {{ number_format($activityData['amount'], 2) }} €

@component('mail::button', ['url' => route('admin.orders.show', $activityData['id'])])
Voir la commande
@endcomponent

@elseif($activityType === 'devis_request')
**Nouvelle Demande de Devis**

Client : {{ $activityData['name'] }}  
Email : {{ $activityData['email'] }}  
Type de bâtiment : {{ $activityData['type_batiment'] }}

@component('mail::button', ['url' => route('admin.devis.show', $activityData['id'])])
Voir le devis
@endcomponent

@elseif($activityType === 'formation_inscription')
**Nouvelle Inscription à une Formation**

Participant : {{ $activityData['name'] }}  
Email : {{ $activityData['email'] }}  
Formation : {{ $activityData['formation_title'] }}

@component('mail::button', ['url' => route('admin.formations.inscriptions')])
Voir les inscriptions
@endcomponent

@elseif($activityType === 'contact_form')
**Nouveau Message de Contact**

De : {{ $activityData['name'] }}  
Email : {{ $activityData['email'] }}  
Sujet : {{ $activityData['subject'] }}

@component('mail::button', ['url' => route('admin.contacts.index')])
Voir les messages
@endcomponent

@endif

Merci,<br>
{{ config('app.name') }}
@endcomponent

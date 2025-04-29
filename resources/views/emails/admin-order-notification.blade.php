@component('mail::message')
# Nouvelle Commande

Une nouvelle commande a été passée sur NE DON ENERGY.

**Détails de la commande :**
- Numéro de commande : {{ $order->id }}
- Produit : {{ $order->product->name }}
- Quantité : {{ $order->quantity }}
- Prix total : {{ number_format($order->total_price, 2) }} CFA

**Informations client :**
- Nom : <a href="{{ route('admin.users.show', $order->user_id) }}">{{ $order->customer_name }}</a>
- Email : {{ $order->customer_email }}
- Téléphone : {{ $order->customer_phone }}
- Adresse : {{ $order->customer_address }}

**Paiement :**
- Méthode : {{ $order->payment_method }}
- Statut : {{ ucfirst($order->status) }}

Vous pouvez gérer cette commande depuis le panneau d'administration.

@component('mail::button', ['url' => route('admin.orders.show', $order->id)])
Voir la commande 
@endcomponent

Merci,<br>
{{ config('app.name') }}
@endcomponent
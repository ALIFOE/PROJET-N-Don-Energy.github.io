<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-green-500 text-6xl"></i>
                        </div>
                        <h1 class="text-3xl font-bold mb-4">Commande confirmée !</h1>
                        <p class="text-xl mb-4">Votre commande #{{ $order->id }} a été enregistrée avec succès.</p>
                        <p class="mb-4">Un email de confirmation a été envoyé à {{ $order->customer_email }}</p>
                        
                        <div class="bg-gray-50 p-6 rounded-lg mb-6">
                            <h2 class="text-xl font-semibold mb-4">Récapitulatif de la commande</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h3 class="font-semibold mb-2">Détails du produit</h3>
                                    <p class="mb-1"><span class="font-medium">Produit :</span> {{ $order->product->nom }}</p>
                                    <p class="mb-1"><span class="font-medium">Quantité :</span> {{ $order->quantity }}</p>
                                    <p class="mb-1"><span class="font-medium">Prix unitaire :</span> {{ number_format($order->product->prix, 0, ',', ' ') }} FCFA</p>
                                    <p class="mb-1"><span class="font-medium">Total :</span> {{ number_format($order->total_price, 0, ',', ' ') }} FCFA</p>
                                    <p class="mb-1"><span class="font-medium">Méthode de paiement :</span> {{ ucfirst($order->payment_method) }}</p>
                                    <p class="mb-1"><span class="font-medium">Statut :</span> 
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($order->status === 'completed') bg-green-100 text-green-800
                                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <h3 class="font-semibold mb-2">Informations de livraison</h3>
                                    <p class="mb-1"><span class="font-medium">Nom :</span> {{ $order->customer_name }}</p>
                                    <p class="mb-1"><span class="font-medium">Email :</span> {{ $order->customer_email }}</p>
                                    <p class="mb-1"><span class="font-medium">Téléphone :</span> {{ $order->customer_phone }}</p>
                                    <p class="mb-1"><span class="font-medium">Adresse :</span> {{ $order->customer_address }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-center space-x-4">
                            <a href="{{ route('market-place') }}" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                                Retour à la boutique
                            </a>
                            <a href="{{ route('home') }}" class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700">
                                Retour à l'accueil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
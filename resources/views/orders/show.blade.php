<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">Détails de la commande #{{ $order->id }}</h1>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full 
                            @if($order->status === 'completed') bg-green-100 text-green-800
                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h2 class="text-lg font-semibold mb-4">Informations de la commande</h2>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500">Date de commande</p>
                                    <p class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Méthode de paiement</p>
                                    <p class="font-medium">{{ ucfirst($order->payment_method) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">ID de transaction</p>
                                    <p class="font-medium">{{ $order->transaction_id ?? 'Non disponible' }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h2 class="text-lg font-semibold mb-4">Détails du produit</h2>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500">Produit</p>
                                    <p class="font-medium">{{ $order->product->nom }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Quantité</p>
                                    <p class="font-medium">{{ $order->quantity }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Prix unitaire</p>
                                    <p class="font-medium">{{ number_format($order->product->prix, 0, ',', ' ') }} FCFA</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Prix total</p>
                                    <p class="font-medium text-lg">{{ number_format($order->total_price, 0, ',', ' ') }} FCFA</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Retour aux commandes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
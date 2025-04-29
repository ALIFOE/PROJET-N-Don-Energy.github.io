<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">Détails de la commande #{{ $order->id }}</h1>
                        <a href="{{ route('admin.orders.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                            Retour à la liste
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h2 class="text-xl font-semibold mb-4">Informations de la commande</h2>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="mb-2"><strong>Date :</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                <p class="mb-2"><strong>Statut :</strong> 
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </p>
                                <p class="mb-2"><strong>Méthode de paiement :</strong> {{ ucfirst($order->payment_method) }}</p>
                            </div>
                        </div>

                        <div>
                            <h2 class="text-xl font-semibold mb-4">Informations client</h2>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="mb-2"><strong>Nom :</strong> {{ $order->customer_name }}</p>
                                <p class="mb-2"><strong>Email :</strong> {{ $order->customer_email }}</p>
                                <p class="mb-2"><strong>Téléphone :</strong> {{ $order->customer_phone }}</p>
                                <p class="mb-2"><strong>Adresse :</strong> {{ $order->customer_address }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h2 class="text-xl font-semibold mb-4">Détails du produit</h2>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="mb-2"><strong>Produit :</strong> {{ $order->product->nom }}</p>
                            <p class="mb-2"><strong>Quantité :</strong> {{ $order->quantity }}</p>
                            <p class="mb-2"><strong>Prix unitaire :</strong> {{ number_format($order->product->prix, 0, ',', ' ') }} FCFA</p>
                            <p class="mb-2"><strong>Total :</strong> {{ number_format($order->total_price, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h2 class="text-xl font-semibold mb-4">Mettre à jour le statut</h2>
                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="flex items-center gap-4">
                            @csrf
                            @method('PUT')
                            <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>En cours de traitement</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Terminée</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Annulée</option>
                            </select>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                Mettre à jour
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
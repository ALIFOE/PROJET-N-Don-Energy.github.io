<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-center">
                        <div class="mb-6">
                            <i class="fas fa-check-circle text-green-500 text-6xl"></i>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ __('Commande effectuée avec succès !') }}</h1>
                        <p class="text-lg text-gray-600 mb-8">{{ __('Merci pour votre commande. Un email de confirmation vous a été envoyé.') }}</p>
                        <div class="bg-gray-50 p-6 rounded-lg mb-8">
                            <h2 class="text-xl font-semibold mb-4">{{ __('Détails de la commande') }}</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                                <div>
                                    <p class="text-gray-600">{{ __('Numéro de commande') }}</p>
                                    <p class="font-medium">{{ $order->id }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">{{ __('Total') }}</p>
                                    <p class="font-medium">{{ number_format($order->total_price, 0, ',', ' ') }} FCFA</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">{{ __('Produit') }}</p>
                                    <p class="font-medium">{{ $order->product->nom }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">{{ __('Statut') }}</p>
                                    <p class="font-medium">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ __('En traitement') }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-center space-x-4">
                            <a href="{{ route('orders.show', $order->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700">
                                {{ __('Voir les détails') }}
                            </a>
                            <a href="{{ route('market-place') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-gray-700 hover:bg-gray-300">
                                {{ __('Continuer vos achats') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
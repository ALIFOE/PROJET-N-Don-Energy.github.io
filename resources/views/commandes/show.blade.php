<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-3xl font-bold">Détails de la commande</h1>
                        <a href="{{ route('mes-commandes') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Retour
                        </a>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <h2 class="text-xl font-semibold mb-4">Informations produit</h2>
                                <p class="mb-2"><span class="font-semibold">Nom :</span> {{ $commande->product->nom }}</p>
                                <p class="mb-2"><span class="font-semibold">Prix unitaire :</span> {{ $commande->product->formatted_price }}</p>
                                <p class="mb-2"><span class="font-semibold">Quantité :</span> {{ $commande->quantity }}</p>
                                <p class="mb-2"><span class="font-semibold">Prix total :</span> {{ number_format($commande->product->prix * $commande->quantity, 2, ',', ' ') }} €</p>
                            </div>                            <div>
                                <h2 class="text-xl font-semibold mb-4">État de la commande</h2>
                                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                    <div class="flex items-center mb-4">
                                        @if($commande->status === 'en_attente')
                                            <span class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-800 inline-flex items-center">
                                                <i class="fas fa-clock mr-2"></i>
                                                En attente de validation
                                            </span>
                                            <p class="ml-4 text-gray-600">Votre commande est en cours d'examen par notre équipe</p>
                                        @elseif($commande->status === 'accepte')
                                            <span class="px-4 py-2 rounded-full bg-green-100 text-green-800 inline-flex items-center">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                Commande validée
                                            </span>
                                            <p class="ml-4 text-gray-600">Votre commande a été validée et est en cours de traitement</p>
                                        @elseif($commande->status === 'refuse')
                                            <span class="px-4 py-2 rounded-full bg-red-100 text-red-800 inline-flex items-center">
                                                <i class="fas fa-times-circle mr-2"></i>
                                                Commande refusée
                                            </span>
                                            <p class="ml-4 text-gray-600">Votre commande n'a pas été acceptée. Veuillez consulter le message ci-dessous pour plus d'informations.</p>
                                        @endif
                                    </div>

                                    <div class="space-y-2">
                                        <p><span class="font-semibold">Date de commande :</span> {{ $commande->created_at->format('d/m/Y H:i') }}</p>
                                        @if($commande->status === 'accepte')
                                            <p><span class="font-semibold">Date de validation :</span> {{ $commande->updated_at->format('d/m/Y H:i') }}</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Étapes de progression -->
                                <div class="relative">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex flex-col items-center">
                                            <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center">
                                                <i class="fas fa-shopping-cart"></i>
                                            </div>
                                            <span class="text-sm mt-1">Commandé</span>
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <div class="w-8 h-8 rounded-full {{ $commande->status != 'en_attente' ? 'bg-green-500' : 'bg-gray-300' }} text-white flex items-center justify-center">
                                                <i class="fas fa-clipboard-check"></i>
                                            </div>
                                            <span class="text-sm mt-1">Validé</span>
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <div class="w-8 h-8 rounded-full {{ $commande->status === 'accepte' ? 'bg-green-500' : 'bg-gray-300' }} text-white flex items-center justify-center">
                                                <i class="fas fa-box"></i>
                                            </div>
                                            <span class="text-sm mt-1">En traitement</span>
                                        </div>
                                    </div>
                                    <div class="absolute top-4 left-0 w-full h-0.5 bg-gray-200">
                                        <div class="h-full bg-green-500 transition-all duration-500" style="width: {{ $commande->status === 'en_attente' ? '0%' : ($commande->status === 'accepte' ? '100%' : '50%') }}"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($commande->message)
                            <div class="mt-6">
                                <h2 class="text-xl font-semibold mb-4">Message</h2>
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <p class="text-gray-700">{{ $commande->message }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

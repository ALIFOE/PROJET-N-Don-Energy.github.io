<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-8 text-center">Mes Commandes</h1>

                    @if($commandes->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500 text-lg">Vous n'avez pas encore passé de commande.</p>
                        </div>
                    @else
                        <div class="grid gap-6">
                            @foreach($commandes as $commande)
                                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-xl font-semibold mb-2">{{ $commande->product->nom }}</h3>
                                            <p class="text-gray-600 mb-2">Commandé le : {{ $commande->created_at->format('d/m/Y H:i') }}</p>
                                            <p class="text-gray-600 mb-2">Prix : {{ $commande->product->formatted_price }}</p>
                                            <p class="text-gray-600 mb-2">Quantité : {{ $commande->quantity }}</p>
                                        </div>
                                        <div>
                                            @if($commande->status === 'en_attente')
                                                <div class="text-center">
                                                    <span class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-800 flex items-center">
                                                        <i class="fas fa-clock mr-2"></i>
                                                        En attente de validation
                                                    </span>
                                                    <p class="text-sm text-gray-500 mt-2">Votre commande est en cours d'examen</p>
                                                </div>
                                            @elseif($commande->status === 'accepte')
                                                <div class="text-center">
                                                    <span class="px-4 py-2 rounded-full bg-green-100 text-green-800 flex items-center">
                                                        <i class="fas fa-check-circle mr-2"></i>
                                                        Commande validée
                                                    </span>
                                                    <p class="text-sm text-green-600 mt-2">Votre commande a été acceptée</p>
                                                </div>
                                            @elseif($commande->status === 'refuse')
                                                <div class="text-center">
                                                    <span class="px-4 py-2 rounded-full bg-red-100 text-red-800 flex items-center">
                                                        <i class="fas fa-times-circle mr-2"></i>
                                                        Commande refusée
                                                    </span>
                                                    <p class="text-sm text-red-600 mt-2">Votre commande n'a pas été acceptée</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @if($commande->message)
                                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                            <p class="text-gray-700"><span class="font-semibold">Message :</span> {{ $commande->message }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

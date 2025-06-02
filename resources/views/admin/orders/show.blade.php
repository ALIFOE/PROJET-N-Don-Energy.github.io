<x-app-layout>
    <!-- Script pour gérer la confirmation d'annulation et de suppression -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion du modal de mise à jour de statut
            let originalStatus = '';
            const cancelModal = document.getElementById('cancelModal');
            const statusSelect = document.getElementById('statusSelect');
            const updateButton = document.getElementById('updateButton');
            const form = statusSelect.closest('form');

            // Sauvegarde le statut original au chargement
            originalStatus = statusSelect.value;

            // Gestion du modal de suppression
            const deleteButton = document.getElementById('deleteButton');
            const deleteModal = document.getElementById('deleteModal');
            const cancelDelete = document.getElementById('cancelDelete');

            deleteButton.addEventListener('click', function() {
                deleteModal.classList.remove('hidden');
            });

            cancelDelete.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
            });

            // Gestion du modal de mise à jour de statut
            updateButton.addEventListener('click', function() {
                if (statusSelect.value === '{{ App\Models\Order::STATUS_ANNULE }}') {
                    cancelModal.classList.remove('hidden');
                } else {
                    form.submit();
                }
            });

            document.getElementById('cancelAction').addEventListener('click', function() {
                cancelModal.classList.add('hidden');
                statusSelect.value = originalStatus;
            });

            document.getElementById('confirmCancel').addEventListener('click', function() {
                cancelModal.classList.add('hidden');
                form.submit();
            });
        });
    </script>

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
                                        @if($order->status === App\Models\Order::STATUS_TERMINE) bg-green-100 text-green-800
                                        @elseif($order->status === App\Models\Order::STATUS_EN_ATTENTE) bg-yellow-100 text-yellow-800
                                        @elseif($order->status === App\Models\Order::STATUS_EN_COURS) bg-blue-100 text-blue-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $order->status_label }}
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
                        <h2 class="text-xl font-semibold mb-4">Actions</h2>
                        <div class="flex items-center gap-4">
                            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="flex items-center gap-4">
                                @csrf
                                @method('PUT')
                                <select name="status" id="statusSelect" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="{{ App\Models\Order::STATUS_EN_ATTENTE }}" {{ $order->status === App\Models\Order::STATUS_EN_ATTENTE ? 'selected' : '' }}>En attente</option>
                                    <option value="{{ App\Models\Order::STATUS_EN_COURS }}" {{ $order->status === App\Models\Order::STATUS_EN_COURS ? 'selected' : '' }}>En cours de traitement</option>
                                    <option value="{{ App\Models\Order::STATUS_TERMINE }}" {{ $order->status === App\Models\Order::STATUS_TERMINE ? 'selected' : '' }}>Terminée</option>
                                    <option value="{{ App\Models\Order::STATUS_ANNULE }}" {{ $order->status === App\Models\Order::STATUS_ANNULE ? 'selected' : '' }}>Annulée</option>
                                </select>
                                <button type="button" id="updateButton" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                    Mettre à jour
                                </button>
                            </form>

                            <button type="button" id="deleteButton" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                                Supprimer la commande
                            </button>
                        </div>

                        <!-- Modal de confirmation pour la suppression -->
                        <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full" style="z-index: 100;">
                            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                <div class="mt-3 text-center">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Confirmation de suppression</h3>
                                    <div class="mt-2 px-7 py-3">
                                        <p class="text-sm text-gray-500">
                                            Attention ! La suppression de cette commande est définitive et ne peut pas être annulée. Voulez-vous vraiment supprimer cette commande ?
                                        </p>
                                    </div>
                                    <div class="items-center px-4 py-3">
                                        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="flex justify-center space-x-4">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 mr-2">
                                                Confirmer la suppression
                                            </button>
                                            <button type="button" id="cancelDelete" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                                Annuler
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal de confirmation pour l'annulation -->
                        <div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full" style="z-index: 100;">
                            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                <div class="mt-3 text-center">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Confirmation d'annulation</h3>
                                    <div class="mt-2 px-7 py-3">
                                        <p class="text-sm text-gray-500">
                                            Attention ! L'annulation de la commande la retirera automatiquement de la liste du client. Cette action est irréversible. Voulez-vous continuer ?
                                        </p>
                                    </div>
                                    <div class="items-center px-4 py-3">
                                        <button id="confirmCancel" type="button" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 mr-2">
                                            Confirmer l'annulation
                                        </button>
                                        <button id="cancelAction" type="button" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                            Annuler
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="mt-4 p-4 text-green-800 bg-green-100 border border-green-200 rounded">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mt-4 p-4 text-red-800 bg-red-100 border border-red-200 rounded">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
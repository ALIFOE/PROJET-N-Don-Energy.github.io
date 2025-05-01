<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord administrateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900 text-xl mb-2">Utilisateurs</div>
                    <div class="text-3xl font-bold">{{ $stats['total_users'] }}</div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900 text-xl mb-2">Commandes totales</div>
                    <div class="text-3xl font-bold">{{ $stats['total_orders'] }}</div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900 text-xl mb-2">Commandes en attente</div>
                    <div class="text-3xl font-bold">{{ $stats['pending_orders'] }}</div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900 text-xl mb-2">Messages non lus</div>
                    <div class="text-3xl font-bold">{{ $stats['unread_contacts'] }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Utilisateurs récents -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Utilisateurs récents</h3>
                    <div class="space-y-4">
                        @foreach($stats['recent_users'] as $user)
                            <div class="flex items-center justify-between border-b pb-2">
                                <div>
                                    <div class="font-medium">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $user->email }}</div>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800">Voir tous les utilisateurs →</a>
                    </div>
                </div>

                <!-- Commandes récentes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Commandes récentes</h3>
                    <div class="space-y-4">
                        @foreach($stats['recent_orders'] as $order)
                            <div class="flex items-center justify-between border-b pb-2">
                                <div>
                                    <div class="font-medium">Commande #{{ $order->id }}</div>
                                    <div class="text-sm text-gray-600">{{ $order->user->name }}</div>
                                </div>
                                <div class="text-sm">
                                    <span class="px-2 py-1 rounded-full 
                                        @if($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-800">Voir toutes les commandes →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

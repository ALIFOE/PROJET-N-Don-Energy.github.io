<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord administrateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Vue d'ensemble -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Statistiques globales -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Statistiques globales') }}</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-gray-600">{{ __('Total des utilisateurs') }}</p>
                            <p class="text-2xl font-bold">{{ $totalUsers }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">{{ __('Devis en attente') }}</p>
                            <p class="text-2xl font-bold">{{ $pendingQuotes }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">{{ __('Installations actives') }}</p>
                            <p class="text-2xl font-bold">{{ $activeInstallations }}</p>
                        </div>
                    </div>
                </div>

                <!-- Activités récentes -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Activités récentes') }}</h3>
                    <div class="space-y-4">
                        @forelse($recentActivities as $activity)
                            <div class="border-l-4 border-blue-500 pl-4">
                                <p class="text-sm text-gray-600">{{ $activity->created_at->diffForHumans() }}</p>
                                <p class="text-gray-800">{{ $activity->description }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500">{{ __('Aucune activité récente') }}</p>
                        @endforelse
                    </div>
                </div>

                <!-- Alertes système -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Alertes système') }}</h3>
                    <div class="space-y-4">
                        @forelse($systemAlerts as $alert)
                            <div class="border-l-4 {{ $alert->severity === 'high' ? 'border-red-500' : 'border-yellow-500' }} pl-4">
                                <p class="text-sm font-medium">{{ $alert->title }}</p>
                                <p class="text-sm text-gray-600">{{ $alert->message }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500">{{ __('Aucune alerte') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <a href="{{ route('admin.users.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg p-4 text-center">
                    <i class="fas fa-users mb-2 text-2xl"></i>
                    <p>{{ __('Gérer les utilisateurs') }}</p>
                </a>
                <a href="{{ route('admin.devis.index') }}" class="bg-green-500 hover:bg-green-600 text-white rounded-lg p-4 text-center">
                    <i class="fas fa-file-invoice-dollar mb-2 text-2xl"></i>
                    <p>{{ __('Voir les devis') }}</p>
                </a>
                <a href="{{ route('admin.installations.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white rounded-lg p-4 text-center">
                    <i class="fas fa-solar-panel mb-2 text-2xl"></i>
                    <p>{{ __('Installations') }}</p>
                </a>
                <a href="{{ route('admin.formations.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg p-4 text-center">
                    <i class="fas fa-graduation-cap mb-2 text-2xl"></i>
                    <p>{{ __('Formations') }}</p>
                </a>
            </div>

            <!-- Tableaux de données -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Derniers devis -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">{{ __('Derniers devis') }}</h3>
                        <a href="{{ route('admin.devis.index') }}" class="text-blue-600 hover:text-blue-800">{{ __('Voir tout') }}</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentQuotes as $devis)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $devis->client_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $devis->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $devis->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $devis->status === 'pending' ? __('En attente') : __('Traité') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Dernières installations -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">{{ __('Dernières installations') }}</h3>
                        <a href="{{ route('admin.installations.index') }}" class="text-blue-600 hover:text-blue-800">{{ __('Voir tout') }}</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentInstallations as $installation)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $installation->client_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $installation->type }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $installation->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $installation->status === 'in_progress' ? __('En cours') : __('Terminée') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
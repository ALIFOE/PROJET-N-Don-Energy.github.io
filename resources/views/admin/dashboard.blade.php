@extends('layouts.app')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush

@section('content')
    <!-- Scripts pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Tableau de bord administrateur</h1>

        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Vue d'ensemble -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Carte utilisateurs -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-75">{{ __('Total des utilisateurs') }}</p>
                        <p class="text-3xl font-bold mt-1">{{ $totalUsers }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm opacity-75">
                    <i class="fas fa-chart-line mr-1"></i> +12% ce mois
                </div>
            </div>

            <!-- Carte devis -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-75">{{ __('Devis en attente') }}</p>
                        <p class="text-3xl font-bold mt-1">{{ $pendingQuotes }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <i class="fas fa-file-invoice-dollar text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm opacity-75">
                    <i class="fas fa-clock mr-1"></i> Mis à jour il y a 2h
                </div>
            </div>

            <!-- Carte installations -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-75">{{ __('Installations actives') }}</p>
                        <p class="text-3xl font-bold mt-1">{{ $activeInstallations }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <i class="fas fa-solar-panel text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm opacity-75">
                    <i class="fas fa-bolt mr-1"></i> {{ $activeInstallations }} en production
                </div>
            </div>

            <!-- Carte performance -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-75">{{ __('Performance') }}</p>
                        <p class="text-3xl font-bold mt-1">98%</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <i class="fas fa-chart-pie text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm opacity-75">
                    <i class="fas fa-arrow-up mr-1"></i> +3% vs mois dernier
                </div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Graphique des installations -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('Évolution des installations') }}</h3>
                <canvas id="installationsChart" class="w-full h-64"></canvas>
            </div>

            <!-- Graphique des devis -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('Suivi des devis') }}</h3>
                <canvas id="quotesChart" class="w-full h-64"></canvas>
            </div>
        </div>

        <!-- Activités et alertes -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
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

    @push('scripts')
        <script>
            // Graphique des installations
            const installationsCtx = document.getElementById('installationsChart').getContext('2d');
            new Chart(installationsCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                    datasets: [{
                        label: 'Installations',
                        data: [12, 19, 15, 25, 22, 30],
                        borderColor: 'rgb(147, 51, 234)',
                        tension: 0.3,
                        fill: true,
                        backgroundColor: 'rgba(147, 51, 234, 0.1)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Graphique des devis
            const quotesCtx = document.getElementById('quotesChart').getContext('2d');
            new Chart(quotesCtx, {
                type: 'bar',
                data: {
                    labels: ['En attente', 'En cours', 'Acceptés', 'Refusés'],
                    datasets: [{
                        label: 'Devis',
                        data: [{{ $pendingQuotes }}, 15, 25, 5],
                        backgroundColor: [
                            'rgba(251, 146, 60, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ],
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection

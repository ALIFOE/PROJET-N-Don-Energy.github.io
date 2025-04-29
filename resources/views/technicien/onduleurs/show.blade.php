@extends('layouts.technicien')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Onduleur : {{ $onduleur->modele }}</h1>
        <a href="{{ route('technicien.onduleurs.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
            Retour à la liste
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Informations générales -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Informations générales</h2>
            <dl class="space-y-4">
                <div>
                    <dt class="text-gray-600">Installation</dt>
                    <dd class="font-medium">{{ $onduleur->installation->nom }}</dd>
                </div>
                <div>
                    <dt class="text-gray-600">Modèle</dt>
                    <dd class="font-medium">{{ $onduleur->modele }}</dd>
                </div>
                <div>
                    <dt class="text-gray-600">Puissance nominale</dt>
                    <dd class="font-medium">{{ $onduleur->puissance_nominale }} kW</dd>
                </div>
                <div>
                    <dt class="text-gray-600">Date d'installation</dt>
                    <dd class="font-medium">{{ $onduleur->date_installation->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-gray-600">Statut</dt>
                    <dd class="status-indicator font-medium" data-onduleur-id="{{ $onduleur->id }}">
                        <div class="animate-pulse inline-flex h-3 w-3 rounded-full bg-gray-400 mr-2"></div>
                        Vérification...
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Données en temps réel -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Données en temps réel</h2>
            <div id="realtime-data" class="space-y-4">
                <div class="animate-pulse">
                    <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                    <div class="space-y-3 mt-4">
                        <div class="h-4 bg-gray-200 rounded"></div>
                        <div class="h-4 bg-gray-200 rounded"></div>
                        <div class="h-4 bg-gray-200 rounded"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphique de production -->
        <div class="bg-white p-6 rounded-lg shadow-md md:col-span-2">
            <h2 class="text-xl font-semibold mb-4">Production journalière</h2>
            <div id="production-chart" class="h-96"></div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let productionChart = null;

    function updateStatus() {
        const indicator = document.querySelector('.status-indicator');
        const onduleurId = indicator.dataset.onduleurId;
        
        fetch(`/technicien/onduleurs/${onduleurId}/check-connection`)
            .then(response => response.json())
            .then(data => {
                const color = data.connected ? 'bg-green-500' : 'bg-red-500';
                const status = data.connected ? 'Connecté' : 'Déconnecté';
                indicator.innerHTML = `
                    <div class="inline-flex h-3 w-3 rounded-full ${color} mr-2"></div>
                    ${status}
                `;
            })
            .catch(() => {
                indicator.innerHTML = `
                    <div class="inline-flex h-3 w-3 rounded-full bg-red-500 mr-2"></div>
                    Erreur de connexion
                `;
            });
    }

    function updateRealtimeData() {
        const onduleurId = document.querySelector('.status-indicator').dataset.onduleurId;
        
        fetch(`/technicien/onduleurs/${onduleurId}/realtime-data`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('realtime-data').innerHTML = `
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-gray-600">Puissance actuelle</dt>
                            <dd class="font-medium">${data.current_power} kW</dd>
                        </div>
                        <div>
                            <dt class="text-gray-600">Production du jour</dt>
                            <dd class="font-medium">${data.daily_production} kWh</dd>
                        </div>
                        <div>
                            <dt class="text-gray-600">Température</dt>
                            <dd class="font-medium">${data.temperature}°C</dd>
                        </div>
                        <div>
                            <dt class="text-gray-600">Efficacité</dt>
                            <dd class="font-medium">${data.efficiency}%</dd>
                        </div>
                    </dl>
                `;
            })
            .catch(() => {
                document.getElementById('realtime-data').innerHTML = `
                    <div class="text-red-500">
                        Erreur lors de la récupération des données
                    </div>
                `;
            });
    }

    function updateProductionChart() {
        const onduleurId = document.querySelector('.status-indicator').dataset.onduleurId;
        
        fetch(`/technicien/onduleurs/${onduleurId}/daily-production`)
            .then(response => response.json())
            .then(data => {
                if (productionChart) {
                    productionChart.destroy();
                }

                const ctx = document.getElementById('production-chart').getContext('2d');
                productionChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Production (kW)',
                            data: data.values,
                            borderColor: 'rgb(59, 130, 246)',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Puissance (kW)'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Heure'
                                }
                            }
                        }
                    }
                });
            });
    }

    // Mise à jour initiale
    updateStatus();
    updateRealtimeData();
    updateProductionChart();

    // Mises à jour périodiques
    setInterval(updateStatus, 30000);
    setInterval(updateRealtimeData, 10000);
    setInterval(updateProductionChart, 300000);
});
</script>
@endpush
@endsection
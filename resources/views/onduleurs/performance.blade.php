<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Performances de l\'onduleur') }} {{ $onduleur->modele }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- En-tête avec les informations de base -->
                    <div class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-blue-900">Puissance actuelle</h3>
                                <p class="text-2xl font-bold text-blue-600" id="current-power">-- kW</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-green-900">Production journalière</h3>
                                <p class="text-2xl font-bold text-green-600" id="daily-production">-- kWh</p>
                            </div>
                            <div class="bg-yellow-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-yellow-900">Efficacité</h3>
                                <p class="text-2xl font-bold text-yellow-600" id="efficiency">-- %</p>
                            </div>
                        </div>
                    </div>

                    <!-- Graphiques -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Graphique de production -->
                        <div class="bg-white rounded-lg shadow">
                            <div class="p-4 border-b">
                                <h3 class="text-lg font-medium">Production (24 dernières heures)</h3>
                            </div>
                            <div class="p-4">
                                <canvas id="production-chart" class="w-full" height="300"></canvas>
                            </div>
                        </div>

                        <!-- Graphique de température -->
                        <div class="bg-white rounded-lg shadow">
                            <div class="p-4 border-b">
                                <h3 class="text-lg font-medium">Température</h3>
                            </div>
                            <div class="p-4">
                                <canvas id="temperature-chart" class="w-full" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau des données détaillées -->
                    <div class="mt-6">
                        <h3 class="text-lg font-medium mb-4">Historique détaillé</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horodatage</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Puissance (kW)</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Température (°C)</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Efficacité (%)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($donnees as $donnee)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $donnee->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($donnee->power, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($donnee->temperature, 1) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($donnee->efficiency, 1) }}
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
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Préparer les données pour les graphiques
            const data = @json($donnees);
            const labels = data.map(d => new Date(d.created_at).toLocaleTimeString());
            const powerData = data.map(d => d.power);
            const temperatureData = data.map(d => d.temperature);

            // Graphique de production
            const productionChart = new Chart(document.getElementById('production-chart'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Production (kW)',
                        data: powerData,
                        borderColor: 'rgb(59, 130, 246)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Graphique de température
            const temperatureChart = new Chart(document.getElementById('temperature-chart'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Température (°C)',
                        data: temperatureData,
                        borderColor: 'rgb(239, 68, 68)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Fonction pour mettre à jour les valeurs en temps réel
            function updateData() {
                fetch(`/api/onduleurs/{{ $onduleur->id }}/latest-data`)
                    .then(response => response.json())
                    .then(response => {
                        if (response.success) {
                            const data = response.data;
                            
                            // Mettre à jour les valeurs en direct
                            document.getElementById('current-power').textContent = `${data.power.toFixed(2)} kW`;
                            document.getElementById('daily-production').textContent = `${data.daily_energy.toFixed(2)} kWh`;
                            document.getElementById('efficiency').textContent = `${data.efficiency.toFixed(1)} %`;

                            // Ajouter les nouvelles données aux graphiques
                            const timestamp = new Date(data.timestamp).toLocaleTimeString();
                            
                            // Mettre à jour le graphique de production
                            productionChart.data.labels.push(timestamp);
                            productionChart.data.datasets[0].data.push(data.power);
                            if (productionChart.data.labels.length > 288) { // 24h * 12 (5 minutes)
                                productionChart.data.labels.shift();
                                productionChart.data.datasets[0].data.shift();
                            }
                            productionChart.update();

                            // Mettre à jour le graphique de température
                            temperatureChart.data.labels.push(timestamp);
                            temperatureChart.data.datasets[0].data.push(data.temperature);
                            if (temperatureChart.data.labels.length > 288) {
                                temperatureChart.data.labels.shift();
                                temperatureChart.data.datasets[0].data.shift();
                            }
                            temperatureChart.update();
                        }
                    })
                    .catch(error => console.error('Erreur lors de la récupération des données:', error));
            }

            // Mettre à jour toutes les 5 secondes
            setInterval(updateData, 5000);
        });
    </script>
    @endpush
</x-app-layout>
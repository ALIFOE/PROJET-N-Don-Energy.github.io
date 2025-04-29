<x-app-layout>
    <div class="py-16 mt-16">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900">Suivi de Production</h1>
                <p class="mt-4 text-xl text-gray-600">Visualisez et analysez les performances de vos installations solaires en temps réel</p>
                
                <!-- Bouton Connecter un onduleur -->
                <div class="mt-6">
                    <a href="{{ route('onduleurs.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13 7h-2v2h2V7zm0 4h-2v2h2v-2zm2-1a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6a1 1 0 0 1 1-1h2V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v6h2zm-6-6v6h4V4h-4z" />
                        </svg>
                        {{ __("Connecter un onduleur") }}
                    </a>
                </div>
            </div>

            <!-- Filtres et Export -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <select id="periode" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <option value="24h">Dernières 24 heures</option>
                            <option value="semaine">7 derniers jours</option>
                            <option value="mois">30 derniers jours</option>
                            <option value="annee">Année</option>
                            <option value="personnalise">Période personnalisée</option>
                        </select>

                        <div id="dates-personnalisees" class="hidden flex items-center space-x-2">
                            <input type="datetime-local" id="date-debut" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <span class="text-gray-500">à</span>
                            <input type="datetime-local" id="date-fin" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <button onclick="exportData('pdf')" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-file-pdf mr-2"></i>
                            Exporter en PDF
                        </button>
                        <button onclick="exportData('csv')" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-file-csv mr-2"></i>
                            Exporter en CSV
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cards de statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-700">Production Actuelle</h3>
                        <i class="fas fa-bolt text-yellow-500 text-2xl"></i>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 mt-4" id="currentProduction">-- kW</p>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-700">Production Moyenne</h3>
                        <i class="fas fa-chart-line text-blue-500 text-2xl"></i>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 mt-4" id="averageProduction">-- kW</p>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-700">Production Totale</h3>
                        <i class="fas fa-solar-panel text-green-500 text-2xl"></i>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 mt-4" id="totalProduction">-- kWh</p>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-700">État du Système</h3>
                        <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 mt-4" id="systemStatus">Optimal</p>
                </div>
            </div>

            <!-- Graphiques -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Graphique Production -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Production</h3>
                    <div class="chart-container">
                        <canvas id="productionChart"></canvas>
                    </div>
                </div>

                <!-- Graphique Température et Irradiance -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Conditions Environnementales</h3>
                    <div class="chart-container">
                        <canvas id="environmentChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Indicateurs de Performance -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Indicateurs Temps Réel</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="indicator">
                        <i class="fas fa-battery-three-quarters indicator-icon text-blue-500"></i>
                        <div>
                            <p class="text-sm text-gray-600">Niveau Batterie</p>
                            <p class="indicator-value" id="batteryLevel">--%</p>
                        </div>
                    </div>
                    <div class="indicator">
                        <i class="fas fa-thermometer-half indicator-icon text-red-500"></i>
                        <div>
                            <p class="text-sm text-gray-600">Température</p>
                            <p class="indicator-value" id="temperature">--°C</p>
                        </div>
                    </div>
                    <div class="indicator">
                        <i class="fas fa-sun indicator-icon text-yellow-500"></i>
                        <div>
                            <p class="text-sm text-gray-600">Irradiance</p>
                            <p class="indicator-value" id="irradiance">-- W/m²</p>
                        </div>
                    </div>
                    <div class="indicator">
                        <i class="fas fa-tachometer-alt indicator-icon text-green-500"></i>
                        <div>
                            <p class="text-sm text-gray-600">Rendement</p>
                            <p class="indicator-value" id="efficiency">--%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentPeriod = '24h';
        let customStartDate = null;
        let customEndDate = null;

        function updateCharts() {
            const params = new URLSearchParams();
            params.append('periode', currentPeriod);
            
            if (currentPeriod === 'personnalise' && customStartDate && customEndDate) {
                params.append('debut', customStartDate);
                params.append('fin', customEndDate);
            }

            fetch('/suivi-production/data?' + params.toString())
                .then(response => response.json())
                .then(data => {
                    // Mise à jour des statistiques
                    const stats = data.stats;
                    document.getElementById('averageProduction').textContent = 
                        stats.production_moyenne.toFixed(2) + ' kW';
                    document.getElementById('currentProduction').textContent = 
                        Object.values(data.production).pop().toFixed(2) + ' kW';
                    
                    // Mise à jour des graphiques
                    updateProductionChart(data);
                    updateEnvironmentChart(data);
                    
                    // Mise à jour des indicateurs
                    updateIndicators(data);
                })
                .catch(error => console.error('Erreur:', error));
        }

        function updateProductionChart(data) {
            const timestamps = Object.keys(data.production);
            const productionValues = Object.values(data.production);

            if (window.productionChart) {
                window.productionChart.data.labels = timestamps;
                window.productionChart.data.datasets[0].data = productionValues;
                window.productionChart.update();
            } else {
                const ctx = document.getElementById('productionChart').getContext('2d');
                window.productionChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: timestamps,
                        datasets: [{
                            label: 'Production (kW)',
                            data: productionValues,
                            borderColor: '#1e88e5',
                            backgroundColor: 'rgba(30, 136, 229, 0.1)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Production (kW)'
                                }
                            }
                        }
                    }
                });
            }
        }

        function updateEnvironmentChart(data) {
            const timestamps = Object.keys(data.temperature);
            const temperatureValues = Object.values(data.temperature);
            const irradianceValues = Object.values(data.irradiance);

            if (window.environmentChart) {
                window.environmentChart.data.labels = timestamps;
                window.environmentChart.data.datasets[0].data = irradianceValues;
                window.environmentChart.data.datasets[1].data = temperatureValues;
                window.environmentChart.update();
            } else {
                const ctx = document.getElementById('environmentChart').getContext('2d');
                window.environmentChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: timestamps,
                        datasets: [{
                            label: 'Irradiance (W/m²)',
                            data: irradianceValues,
                            borderColor: '#f59e0b',
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y'
                        }, {
                            label: 'Température (°C)',
                            data: temperatureValues,
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y1'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        },
                        scales: {
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'Irradiance (W/m²)'
                                }
                            },
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                title: {
                                    display: true,
                                    text: 'Température (°C)'
                                },
                                grid: {
                                    drawOnChartArea: false
                                }
                            }
                        }
                    }
                });
            }
        }

        function updateIndicators(data) {
            const lastTimestamp = Object.keys(data.production).pop();
            if (lastTimestamp) {
                document.getElementById('temperature').textContent = 
                    data.temperature[lastTimestamp].toFixed(1) + '°C';
                document.getElementById('irradiance').textContent = 
                    Math.round(data.irradiance[lastTimestamp]) + ' W/m²';
                document.getElementById('batteryLevel').textContent = 
                    Math.round(data.batteryLevels[lastTimestamp]) + '%';
                
                // Calculer le rendement approximatif
                const efficiency = (data.production[lastTimestamp] * 1000 / data.irradiance[lastTimestamp] * 100).toFixed(1);
                document.getElementById('efficiency').textContent = efficiency + '%';
            }
        }

        function exportData(format) {
            const params = new URLSearchParams();
            params.append('periode', currentPeriod);
            
            if (currentPeriod === 'personnalise' && customStartDate && customEndDate) {
                params.append('debut', customStartDate);
                params.append('fin', customEndDate);
            }

            const url = format === 'pdf' ? 
                '/suivi-production/export-pdf?' + params.toString() :
                '/suivi-production/export-csv?' + params.toString();
            
            window.location.href = url;
        }

        // Event Listeners
        document.getElementById('periode').addEventListener('change', function(e) {
            currentPeriod = e.target.value;
            const datesPersonnalisees = document.getElementById('dates-personnalisees');
            
            if (currentPeriod === 'personnalise') {
                datesPersonnalisees.classList.remove('hidden');
            } else {
                datesPersonnalisees.classList.add('hidden');
                updateCharts();
            }
        });

        document.getElementById('date-debut').addEventListener('change', function(e) {
            customStartDate = e.target.value;
            if (customStartDate && customEndDate) {
                updateCharts();
            }
        });

        document.getElementById('date-fin').addEventListener('change', function(e) {
            customEndDate = e.target.value;
            if (customStartDate && customEndDate) {
                updateCharts();
            }
        });

        // Initialisation
        updateCharts();

        // Mise à jour automatique toutes les 5 minutes pour les données en temps réel
        setInterval(() => {
            if (currentPeriod === '24h') {
                updateCharts();
            }
        }, 300000);
    </script>
    @endpush
</x-app-layout>

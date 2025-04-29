<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-bold mb-6">Rapports et Analyses</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="card bg-white p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Analyse de Production</h3>
                            <div class="h-80">
                                <canvas id="productionChart"></canvas>
                            </div>
                        </div>
                        
                        <div class="card bg-white p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Analyse de Performance</h3>
                            <div class="h-80">
                                <canvas id="rendementChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const fetchData = async () => {
            try {
                const response = await fetch('{{ route("performances.regionales.data") }}');
                const data = await response.json();
                
                // Graphique de production
                const productionCtx = document.getElementById('productionChart').getContext('2d');
                new Chart(productionCtx, {
                    type: 'line',
                    data: {
                        labels: data.heures.map(h => h + 'h'),
                        datasets: [{
                            label: 'Production (kW)',
                            data: data.production,
                            borderColor: '#1e88e5',
                            backgroundColor: 'rgba(30, 136, 229, 0.1)',
                            fill: true,
                            tension: 0.4
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
                            }
                        },
                        plugins: {
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        }
                    }
                });

                // Graphique de rendement
                const rendementCtx = document.getElementById('rendementChart').getContext('2d');
                new Chart(rendementCtx, {
                    type: 'line',
                    data: {
                        labels: data.heures.map(h => h + 'h'),
                        datasets: [{
                            label: 'Rendement (%)',
                            data: data.rendement,
                            borderColor: '#4caf50',
                            backgroundColor: 'rgba(76, 175, 80, 0.1)',
                            fill: true,
                            tension: 0.4
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
                                    text: 'Rendement (%)'
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Erreur lors de la récupération des données:', error);
            }
        };

        // Charger les données au chargement de la page
        fetchData();

        // Actualiser les données toutes les 5 minutes
        setInterval(fetchData, 5 * 60 * 1000);
    </script>
    @endpush
</x-app-layout>
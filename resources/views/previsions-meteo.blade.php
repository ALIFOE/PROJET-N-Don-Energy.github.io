<x-app-layout>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-3xl font-bold mb-8 text-blue-600">Prévisions Météo</h1>

                    <!-- Conditions actuelles -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">Conditions actuelles</h2>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Bloc d'informations actuelles -->
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <div class="flex items-center">
                                            <div class="relative w-20 h-20 mr-4">
                                                <svg class="w-20 h-20 transform -rotate-90">
                                                    <circle
                                                        class="text-blue-300"
                                                        stroke-width="8"
                                                        stroke="currentColor"
                                                        fill="transparent"
                                                        r="34"
                                                        cx="40"
                                                        cy="40"
                                                    />
                                                    <circle
                                                        class="text-yellow-300"
                                                        stroke-width="8"
                                                        stroke="currentColor"
                                                        fill="transparent"
                                                        r="34"
                                                        cx="40"
                                                        cy="40"
                                                        stroke-dasharray="213.52"
                                                        stroke-dashoffset="53.38"
                                                    />
                                                </svg>
                                                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                                    <i class="fas fa-sun text-3xl text-yellow-300"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-sm">Température</p>
                                                <p class="text-2xl font-bold" data-value="temperature">24°C</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex items-center">
                                            <div class="relative w-20 h-20 mr-4">
                                                <svg class="w-20 h-20 transform -rotate-90">
                                                    <circle
                                                        class="text-blue-300"
                                                        stroke-width="8"
                                                        stroke="currentColor"
                                                        fill="transparent"
                                                        r="34"
                                                        cx="40"
                                                        cy="40"
                                                    />
                                                    <circle
                                                        class="text-indigo-300"
                                                        stroke-width="8"
                                                        stroke="currentColor"
                                                        fill="transparent"
                                                        r="34"
                                                        cx="40"
                                                        cy="40"
                                                        stroke-dasharray="213.52"
                                                        stroke-dashoffset="170.82"
                                                    />
                                                </svg>
                                                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                                    <i class="fas fa-cloud text-3xl text-indigo-300"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-sm">Couverture nuageuse</p>
                                                <p class="text-2xl font-bold" data-value="couverture_nuageuse">20%</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex items-center">
                                            <div class="relative w-20 h-20 mr-4">
                                                <svg class="w-20 h-20 transform -rotate-90">
                                                    <circle
                                                        class="text-blue-300"
                                                        stroke-width="8"
                                                        stroke="currentColor"
                                                        fill="transparent"
                                                        r="34"
                                                        cx="40"
                                                        cy="40"
                                                    />
                                                    <circle
                                                        class="text-teal-300"
                                                        stroke-width="8"
                                                        stroke="currentColor"
                                                        fill="transparent"
                                                        r="34"
                                                        cx="40"
                                                        cy="40"
                                                        stroke-dasharray="213.52"
                                                        stroke-dashoffset="149.46"
                                                    />
                                                </svg>
                                                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                                    <i class="fas fa-wind text-3xl text-teal-300"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-sm">Vent</p>
                                                <p class="text-2xl font-bold" data-value="vitesse_vent">10 km/h</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex items-center">
                                            <div class="relative w-20 h-20 mr-4">
                                                <svg class="w-20 h-20 transform -rotate-90">
                                                    <circle
                                                        class="text-blue-300"
                                                        stroke-width="8"
                                                        stroke="currentColor"
                                                        fill="transparent"
                                                        r="34"
                                                        cx="40"
                                                        cy="40"
                                                    />
                                                    <circle
                                                        class="text-green-300"
                                                        stroke-width="8"
                                                        stroke="currentColor"
                                                        fill="transparent"
                                                        r="34"
                                                        cx="40"
                                                        cy="40"
                                                        stroke-dasharray="213.52"
                                                        stroke-dashoffset="10.68"
                                                    />
                                                </svg>
                                                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                                    <i class="fas fa-solar-panel text-3xl text-green-300"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-sm">Production estimée</p>
                                                <p class="text-2xl font-bold" data-value="production_estimee">95%</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Graphique météo -->
                            <div class="bg-white rounded-lg p-6 shadow-lg border border-gray-200 relative z-10" style="background-color: rgba(255, 255, 255, 0.95);">
                                <h3 class="text-xl font-semibold mb-4 text-gray-800">Évolution météorologique</h3>
                                <div class="relative">
                                    <canvas id="meteoChart" class="w-full h-64"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Prévisions sur 7 jours -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">Prévisions sur 7 jours</h2>
                        <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
                            <div class="bg-white p-4 rounded-lg shadow">
                                <p class="text-sm font-semibold mb-2">Lun 28</p>
                                <i class="fas fa-sun text-yellow-400 text-3xl mb-2"></i>
                                <p class="text-lg font-bold">25°C</p>
                                <p class="text-sm text-gray-600">Ensoleillé</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow">
                                <p class="text-sm font-semibold mb-2">Mar 29</p>
                                <i class="fas fa-cloud-sun text-gray-400 text-3xl mb-2"></i>
                                <p class="text-lg font-bold">23°C</p>
                                <p class="text-sm text-gray-600">Nuageux</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow">
                                <p class="text-sm font-semibold mb-2">Mer 30</p>
                                <i class="fas fa-sun text-yellow-400 text-3xl mb-2"></i>
                                <p class="text-lg font-bold">24°C</p>
                                <p class="text-sm text-gray-600">Ensoleillé</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow">
                                <p class="text-sm font-semibold mb-2">Jeu 1</p>
                                <i class="fas fa-sun text-yellow-400 text-3xl mb-2"></i>
                                <p class="text-lg font-bold">26°C</p>
                                <p class="text-sm text-gray-600">Ensoleillé</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow">
                                <p class="text-sm font-semibold mb-2">Ven 2</p>
                                <i class="fas fa-cloud-sun text-gray-400 text-3xl mb-2"></i>
                                <p class="text-lg font-bold">22°C</p>
                                <p class="text-sm text-gray-600">Nuageux</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow">
                                <p class="text-sm font-semibold mb-2">Sam 3</p>
                                <i class="fas fa-cloud text-gray-400 text-3xl mb-2"></i>
                                <p class="text-lg font-bold">21°C</p>
                                <p class="text-sm text-gray-600">Couvert</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow">
                                <p class="text-sm font-semibold mb-2">Dim 4</p>
                                <i class="fas fa-sun text-yellow-400 text-3xl mb-2"></i>
                                <p class="text-lg font-bold">25°C</p>
                                <p class="text-sm text-gray-600">Ensoleillé</p>
                            </div>
                        </div>
                    </div>

                    <!-- Impact sur la production -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">Impact sur la production</h2>
                        <div class="bg-white shadow rounded-lg p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="font-semibold text-lg mb-4">Production estimée aujourd'hui</h3>
                                    <div class="relative pt-1">
                                        <div class="flex mb-2 items-center justify-between">
                                            <div>
                                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-green-600 bg-green-200">
                                                    Excellent
                                                </span>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-xs font-semibold inline-block text-green-600">
                                                    95%
                                                </span>
                                            </div>
                                        </div>
                                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-green-200">
                                            <div style="width: 95%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500"></div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg mb-4">Prévision hebdomadaire</h3>
                                    <div class="relative pt-1">
                                        <div class="flex mb-2 items-center justify-between">
                                            <div>
                                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-200">
                                                    Très bon
                                                </span>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-xs font-semibold inline-block text-blue-600">
                                                    85%
                                                </span>
                                            </div>
                                        </div>
                                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-blue-200">
                                            <div style="width: 85%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alertes météo -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Alertes météo</h2>
                        <div class="bg-white shadow rounded-lg overflow-hidden">
                            <div class="p-4 border-b" id="alertesContainer">
                                <p class="text-green-600" id="aucuneAlerte">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Aucune alerte météo pour les prochains jours
                                </p>
                            </div>
                            <div class="p-4">
                                <a href="{{ route('meteo.alertes.config') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition inline-block">
                                    <i class="fas fa-cog mr-2"></i>Configurer les alertes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour mettre à jour les alertes
    function updateAlertes(alertes) {
        const alertesContainer = document.getElementById('alertesContainer');
        const aucuneAlerte = document.getElementById('aucuneAlerte');
        
        if (alertes && alertes.length > 0) {
            aucuneAlerte.style.display = 'none';
            alertes.forEach(alerte => {
                const alerteElement = document.createElement('div');
                alerteElement.className = `text-${alerte.type === 'danger' ? 'red' : 'yellow'}-600 mb-2`;
                alerteElement.innerHTML = `
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    ${alerte.message}
                `;
                alertesContainer.appendChild(alerteElement);
            });
        } else {
            aucuneAlerte.style.display = 'block';
        }
    }

    // Fonction pour charger les données météo
    function chargerDonneesMeteo() {
        fetch('{{ route("meteo.donnees-actuelles") }}')
            .then(response => response.json())
            .then(data => {
                // Mise à jour des valeurs actuelles
                document.querySelector('[data-value="temperature"]').textContent = data.temperature + '°C';
                document.querySelector('[data-value="humidite"]').textContent = data.humidite + '%';
                document.querySelector('[data-value="vitesse_vent"]').textContent = data.vitesse_vent + ' km/h';
                document.querySelector('[data-value="couverture_nuageuse"]').textContent = data.couverture_nuageuse + '%';
                document.querySelector('[data-value="production_estimee"]').textContent = data.production_estimee + '%';

                // Mise à jour des alertes
                updateAlertes(data.alertes);

                // Mise à jour du graphique
                if (data.historique && data.historique.length > 0) {
                    const ctx = document.getElementById('meteoChart').getContext('2d');
                    const chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.historique.map(d => d.heure),
                            datasets: [
                                {
                                    label: 'Température (°C)',
                                    data: data.historique.map(d => d.temperature),
                                    borderColor: '#FF6B6B',
                                    backgroundColor: 'rgba(255, 107, 107, 0.1)',
                                    fill: true
                                },
                                {
                                    label: 'Humidité (%)',
                                    data: data.historique.map(d => d.humidite),
                                    borderColor: '#4ECDC4',
                                    backgroundColor: 'rgba(78, 205, 196, 0.1)',
                                    fill: true
                                },
                                {
                                    label: 'Vitesse du vent (km/h)',
                                    data: data.historique.map(d => d.vitesse_vent),
                                    borderColor: '#9D50BB',
                                    backgroundColor: 'rgba(157, 80, 187, 0.1)',
                                    fill: true
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top'
                                }
                            }
                        }
                    });
                }
            })
            .catch(error => console.error('Erreur:', error));
    }

    // Charger les données initiales
    chargerDonneesMeteo();

    // Mettre à jour toutes les 5 minutes
    setInterval(chargerDonneesMeteo, 300000);
});
</script>
@endsection
</x-app-layout>
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-3xl font-semibold text-gray-800">Système Intelligent</h1>
                <p class="text-gray-600 mt-2">Surveillance et gestion intelligente de votre installation</p>
            </div>
        </div>

        <!-- Alertes automatiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-bell text-yellow-500 text-2xl mr-3"></i>
                        <h2 class="text-xl font-semibold">Alertes Automatiques</h2>
                    </div>
                    <div id="alertesContainer" class="space-y-4">
                        <div class="text-gray-500 text-center" id="aucuneAlerte">
                            Aucune alerte active
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('meteo.alertes.config') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            <i class="fas fa-cog mr-2"></i>
                            Configurer les alertes
                        </a>
                    </div>
                </div>
            </div>

            <!-- Diagnostic intelligent -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-chart-line text-green-500 text-2xl mr-3"></i>
                        <h2 class="text-xl font-semibold">Diagnostic Intelligent</h2>
                    </div>
                    <div id="diagnosticContainer" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm font-medium text-gray-500">Performance</p>
                                <p class="text-2xl font-bold mt-1" id="performanceScore">--</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm font-medium text-gray-500">État</p>
                                <p class="text-2xl font-bold mt-1" id="systemStatus">--</p>
                            </div>
                        </div>
                        <div id="diagnosticDetails" class="mt-4">
                            <h3 class="font-medium mb-2">Analyse du système</h3>
                            <ul class="space-y-2 text-sm" id="diagnosticList">
                                <!-- Les diagnostics seront ajoutés ici dynamiquement -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Planification des maintenances -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-tools text-blue-500 text-2xl mr-3"></i>
                        <h2 class="text-xl font-semibold">Planification des Maintenances</h2>
                    </div>
                    <div class="space-y-4">
                        <div id="nextMaintenance" class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="font-medium text-blue-800">Prochaine maintenance</h3>
                            <p class="text-sm text-blue-600 mt-1" id="nextMaintenanceDate">Aucune maintenance planifiée</p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('maintenance-predictive') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                <i class="fas fa-calendar-plus mr-2"></i>
                                Gérer les maintenances
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommandations -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4">Recommandations d'Optimisation</h2>
                <div id="recommendationsContainer" class="space-y-4">
                    <!-- Les recommandations seront ajoutées ici dynamiquement -->
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour charger les alertes
    function updateAlertes() {
        fetch('/api/alertes')
            .then(response => response.json())
            .then(data => {
                const alertesContainer = document.getElementById('alertesContainer');
                const aucuneAlerte = document.getElementById('aucuneAlerte');
                
                if (data && data.length > 0) {
                    aucuneAlerte.style.display = 'none';
                    alertesContainer.innerHTML = data.map(alerte => `
                        <div class="bg-${alerte.niveau}-50 border border-${alerte.niveau}-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-${alerte.niveau}-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-${alerte.niveau}-800">${alerte.type}</h3>
                                    <p class="text-sm text-${alerte.niveau}-700 mt-1">${alerte.description}</p>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    aucuneAlerte.style.display = 'block';
                }
            });
    }

    // Fonction pour mettre à jour le diagnostic
    function updateDiagnostic() {
        fetch('/api/diagnostic')
            .then(response => response.json())
            .then(data => {
                document.getElementById('performanceScore').textContent = `${data.performance}%`;
                document.getElementById('systemStatus').textContent = data.status;
                
                const diagnosticList = document.getElementById('diagnosticList');
                diagnosticList.innerHTML = data.details.map(detail => `
                    <li class="flex items-start">
                        <span class="flex-shrink-0 h-5 w-5 text-${detail.status}-500">
                            <i class="fas fa-${detail.status === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                        </span>
                        <span class="ml-2 text-gray-600">${detail.message}</span>
                    </li>
                `).join('');
            });
    }

    // Fonction pour mettre à jour les maintenances
    function updateMaintenances() {
        fetch('/api/maintenances/next')
            .then(response => response.json())
            .then(data => {
                const nextMaintenanceDate = document.getElementById('nextMaintenanceDate');
                if (data.date) {
                    nextMaintenanceDate.textContent = `${data.type} prévue le ${data.date}`;
                }
            });
    }

    // Fonction pour mettre à jour les recommandations
    function updateRecommendations() {
        fetch('/api/recommendations')
            .then(response => response.json())
            .then(data => {
                const recommendationsContainer = document.getElementById('recommendationsContainer');
                recommendationsContainer.innerHTML = data.map(rec => `
                    <div class="bg-white border rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-lightbulb text-yellow-400 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-gray-900">${rec.title}</h4>
                                <p class="mt-1 text-gray-600">${rec.description}</p>
                                ${rec.action ? `
                                    <a href="${rec.action.url}" class="inline-flex items-center mt-3 text-sm text-blue-600 hover:text-blue-800">
                                        ${rec.action.label}
                                        <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `).join('');
            });
    }

    // Initialisation
    updateAlertes();
    updateDiagnostic();
    updateMaintenances();
    updateRecommendations();

    // Mise à jour périodique
    setInterval(updateAlertes, 60000); // Toutes les minutes
    setInterval(updateDiagnostic, 300000); // Toutes les 5 minutes
    setInterval(updateMaintenances, 3600000); // Toutes les heures
    setInterval(updateRecommendations, 3600000); // Toutes les heures
});
</script>
@endpush
@endsection

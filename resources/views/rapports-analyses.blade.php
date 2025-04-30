@push('scripts')
<script>
// Fonction pour obtenir le token CSRF
function getCsrfToken() {
    const tokenCookie = document.cookie.split('; ').find(row => row.startsWith('XSRF-TOKEN='));
    return tokenCookie ? decodeURIComponent(tokenCookie.split('=')[1]) : null;
}

// Fonction pour les données régionales
async function updateRegionalData() {
    try {
        const token = getCsrfToken();
        if (!token) {
            throw new Error('Token CSRF non trouvé');
        }

        const data = await fetch('/api/regional-performance', {
            method: 'GET',
            credentials: 'include',
            headers: {
                'Accept': 'application/json',
                'X-XSRF-TOKEN': token
            }
        });

        if (!data.ok) {
            throw new Error('Erreur réseau');
        }

        const result = await data.json();
        
        // Mise à jour des données régionales
        document.getElementById('production-regionale').textContent = result.production + ' kW';
        document.getElementById('irradiation').textContent = result.irradiation + ' W/m²';
        document.getElementById('performance-collective').textContent = result.performance + ' %';
        
        // Ajout d'un timestamp
        const timestamp = new Date().toLocaleTimeString();
        document.querySelectorAll('.update-time').forEach(el => {
            el.textContent = `Dernière mise à jour: ${timestamp}`;
        });
    } catch (error) {
        console.error('Erreur lors de la mise à jour des données régionales:', error);
        showNotification('error', 'Erreur', 'Impossible de mettre à jour les données régionales');
    }
}

// Fonction pour les données de l'onduleur
async function updateInverterData() {
    try {
        const token = getCsrfToken();
        if (!token) {
            throw new Error('Token CSRF non trouvé');
        }

        const response = await fetch('/api/inverter-status', {
            credentials: 'include',
            headers: {
                'Accept': 'application/json',
                'X-XSRF-TOKEN': token
            }
        });

        if (!response.ok) {
            throw new Error('Onduleur non connecté');
        }

        const data = await response.json();
        document.getElementById('production-actuelle').textContent = data.currentProduction + ' kW';
        document.getElementById('production-journaliere').textContent = data.dailyProduction + ' kWh';
        document.getElementById('etat-onduleur').textContent = data.status;
    } catch (error) {
        console.error('Erreur lors de la mise à jour des données de l\'onduleur:', error);
        document.getElementById('production-actuelle').textContent = 'Non connecté';
        document.getElementById('production-journaliere').textContent = 'Non connecté';
        document.getElementById('etat-onduleur').textContent = 'Déconnecté';
    }
}

// Fonction pour télécharger un rapport
async function downloadReport(type, period) {
    try {
        const token = getCsrfToken();
        if (!token) {
            throw new Error('Token CSRF non trouvé');
        }

        const response = await fetch(`/api/reports/download/${type}/${period}`, {
            method: 'GET',
            credentials: 'include',
            headers: {
                'Accept': type === 'pdf' ? 'application/pdf' : 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'X-XSRF-TOKEN': token
            }
        });

        if (!response.ok) {
            throw new Error('Erreur lors du téléchargement');
        }

        const blob = await response.blob();
        const fileName = `rapport-${type}-${period}.${type === 'excel' ? 'xlsx' : 'pdf'}`;

        // Pour les PDF, on peut les ouvrir dans un nouvel onglet
        if (type === 'pdf') {
            const pdfUrl = window.URL.createObjectURL(blob);
            window.open(pdfUrl, '_blank');
            setTimeout(() => window.URL.revokeObjectURL(pdfUrl), 1000);
        } else {
            // Pour les autres types de fichiers, on utilise le téléchargement standard
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            setTimeout(() => {
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            }, 1000);
        }
        
        showNotification('success', 'Succès', 'Le rapport a été généré avec succès');
    } catch (error) {
        console.error('Erreur lors du téléchargement:', error);
        showNotification('error', 'Erreur', 'Une erreur est survenue lors du téléchargement du rapport');
    }
}

// Fonction pour sauvegarder les préférences
async function savePreferences() {
    try {
        const token = getCsrfToken();
        if (!token) {
            throw new Error('Token CSRF non trouvé');
        }

        const frequency = document.querySelector('input[name="frequency"]:checked')?.value;
        const formats = Array.from(document.querySelectorAll('input[type="checkbox"]:checked'))
            .map(checkbox => checkbox.nextElementSibling.textContent.trim().toLowerCase());

        if (!frequency) {
            throw new Error('Veuillez sélectionner une fréquence');
        }

        if (formats.length === 0) {
            throw new Error('Veuillez sélectionner au moins un format');
        }

        const response = await fetch('/api/reports/preferences', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-XSRF-TOKEN': token
            },
            body: JSON.stringify({
                frequency,
                formats
            })
        });

        if (!response.ok) {
            throw new Error('Erreur lors de la sauvegarde');
        }

        showNotification('success', 'Succès', 'Vos préférences ont été sauvegardées avec succès');
    } catch (error) {
        console.error('Erreur lors de la sauvegarde des préférences:', error);
        showNotification('error', 'Erreur', error.message || 'Une erreur est survenue lors de la sauvegarde des préférences');
    }
}

// Initialisation lors du chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    // Première mise à jour des données
    updateRegionalData();
    updateInverterData();

    // Mise en place des intervalles de mise à jour
    setInterval(updateRegionalData, 30000); // 30 secondes
    setInterval(updateInverterData, 60000); // 60 secondes

    // Gestionnaires pour les boutons de téléchargement
    document.querySelectorAll('.download-report').forEach(button => {
        button.addEventListener('click', function() {
            const type = this.dataset.type;
            const period = this.dataset.period;
            downloadReport(type, period);
        });
    });

    // Gestionnaire pour le bouton de sauvegarde des préférences
    const saveButton = document.querySelector('#save-preferences');
    if (saveButton) {
        saveButton.addEventListener('click', savePreferences);
    }
});
</script>
@endpush

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-3xl font-bold mb-8 text-blue-600">Rapports et Analyses</h1>

                    <!-- Résumé des performances -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">Performances de l'Onduleur</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-green-50 p-6 rounded-lg">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="font-semibold">Production actuelle</h3>
                                    <i class="fas fa-bolt text-green-500 text-xl"></i>
                                </div>
                                <p id="production-actuelle" class="text-3xl font-bold text-green-600 mb-2">--</p>
                                <p class="text-sm text-green-600">
                                    <i class="fas fa-clock mr-1"></i>
                                    Temps réel
                                </p>
                            </div>
                            <div class="bg-blue-50 p-6 rounded-lg">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="font-semibold">Production journalière</h3>
                                    <i class="fas fa-solar-panel text-blue-500 text-xl"></i>
                                </div>
                                <p id="production-journaliere" class="text-3xl font-bold text-blue-600 mb-2">--</p>
                                <p class="text-sm text-blue-600">
                                    Aujourd'hui
                                </p>
                            </div>
                            <div class="bg-purple-50 p-6 rounded-lg">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="font-semibold">État de l'onduleur</h3>
                                    <i class="fas fa-check-circle text-purple-500 text-xl"></i>
                                </div>
                                <p id="etat-onduleur" class="text-3xl font-bold text-purple-600 mb-2">--</p>
                                <p class="text-sm text-purple-600">
                                    État actuel
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Performances régionales -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">Performances Régionales</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-amber-50 p-6 rounded-lg">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="font-semibold">Production régionale</h3>
                                    <i class="fas fa-sun text-amber-500 text-xl"></i>
                                </div>
                                <p id="production-regionale" class="text-3xl font-bold text-amber-600 mb-2">--</p>
                                <p class="text-sm text-amber-600">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    Production totale de la région
                                </p>
                            </div>
                            <div class="bg-teal-50 p-6 rounded-lg">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="font-semibold">Irradiation solaire</h3>
                                    <i class="fas fa-radiation text-teal-500 text-xl"></i>
                                </div>
                                <p id="irradiation" class="text-3xl font-bold text-teal-600 mb-2">--</p>
                                <p class="text-sm text-teal-600">
                                    Moyenne journalière
                                </p>
                            </div>
                            <div class="bg-indigo-50 p-6 rounded-lg">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="font-semibold">Performance collective</h3>
                                    <i class="fas fa-chart-pie text-indigo-500 text-xl"></i>
                                </div>
                                <p id="performance-collective" class="text-3xl font-bold text-indigo-600 mb-2">--</p>
                                <p class="text-sm text-indigo-600">
                                    Efficacité moyenne régionale
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Graphiques -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">Analyses détaillées</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white p-6 rounded-lg shadow">
                                <h3 class="font-semibold mb-4">Production mensuelle</h3>
                                <div class="aspect-w-16 aspect-h-9 bg-gray-100 rounded-lg">
                                    <!-- Emplacement pour le graphique -->
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-500">Graphique de production mensuelle</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white p-6 rounded-lg shadow">
                                <h3 class="font-semibold mb-4">Comparaison annuelle</h3>
                                <div class="aspect-w-16 aspect-h-9 bg-gray-100 rounded-lg">
                                    <!-- Emplacement pour le graphique -->
                                    <div class="flex items-center justify-center">
                                        <p class="text-gray-500">Graphique de comparaison annuelle</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rapports disponibles -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">Rapports disponibles</h2>
                        <div class="bg-white shadow rounded-lg overflow-hidden">
                            <div class="divide-y divide-gray-200">
                                <div class="p-4 hover:bg-gray-50">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-file-pdf text-red-500 text-xl mr-3"></i>
                                            <div>
                                                <h3 class="font-semibold">Rapport mensuel - Avril 2025</h3>
                                                <p class="text-sm text-gray-500">Généré le 28/04/2025</p>
                                            </div>
                                        </div>
                                        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition download-report" data-type="pdf" data-period="mensuel">
                                            <i class="fas fa-download mr-2"></i>
                                            Télécharger
                                        </button>
                                    </div>
                                </div>
                                <div class="p-4 hover:bg-gray-50">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-file-excel text-green-500 text-xl mr-3"></i>
                                            <div>
                                                <h3 class="font-semibold">Export données brutes - Avril 2025</h3>
                                                <p class="text-sm text-gray-500">Format Excel</p>
                                            </div>
                                        </div>
                                        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition download-report" data-type="excel" data-period="mensuel">
                                            <i class="fas fa-download mr-2"></i>
                                            Télécharger
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Options de personnalisation -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Personnalisation des rapports</h2>
                        <div class="bg-white shadow rounded-lg p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="font-semibold mb-4">Fréquence des rapports</h3>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="frequency" class="form-radio text-blue-600" checked>
                                            <span class="ml-2">Quotidien</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="frequency" class="form-radio text-blue-600">
                                            <span class="ml-2">Hebdomadaire</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="frequency" class="form-radio text-blue-600">
                                            <span class="ml-2">Mensuel</span>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-semibold mb-4">Format préféré</h3>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" class="form-checkbox text-blue-600" checked>
                                            <span class="ml-2">PDF</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" class="form-checkbox text-blue-600">
                                            <span class="ml-2">Excel</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" class="form-checkbox text-blue-600">
                                            <span class="ml-2">CSV</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6">
                                <button id="save-preferences" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 transition">
                                    Sauvegarder les préférences
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
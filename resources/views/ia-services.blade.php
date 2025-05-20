@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- En-tête de la page -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-orange-400 mb-4">Services d'Intelligence Artificielle</h1>
            <p class="text-gray-300 text-xl">Utilisez notre IA pour optimiser vos installations solaires</p>
        </div>

        <!-- Grille de services -->
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Devis IA -->
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-file-invoice text-orange-400 text-2xl mr-3"></i>
                        <h2 class="text-2xl font-bold text-orange-400">Devis Intelligent</h2>
                    </div>
                    <form id="devisForm" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-300">Type de propriété</label>
                                <select name="type_propriete" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300">
                                    <option value="maison">Maison individuelle</option>
                                    <option value="appartement">Appartement</option>
                                    <option value="bureau">Local professionnel</option>
                                    <option value="usine">Site industriel</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-300">Surface habitable (m²)</label>
                                <input type="number" name="surface" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300">
                            </div>
                            <div>
                                <label class="block text-gray-300">Budget approximatif (€)</label>
                                <input type="number" name="budget" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300">
                            </div>
                            <div>
                                <label class="block text-gray-300">Région</label>
                                <input type="text" name="region" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-300">Description complémentaire</label>
                            <textarea name="description" rows="3" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300" placeholder="Détails supplémentaires sur votre projet..."></textarea>
                        </div>
                        <button type="submit" class="w-full px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 flex items-center justify-center">
                            <span class="loading-spinner hidden mr-2">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                            Générer un devis
                        </button>
                    </form>
                    <div id="devisResult" class="mt-4 p-4 bg-gray-700 rounded-md hidden">
                        <pre class="text-gray-300 whitespace-pre-wrap"></pre>
                    </div>
                </div>
            </div>

            <!-- Dimensionnement IA -->
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-ruler-combined text-orange-400 text-2xl mr-3"></i>
                        <h2 class="text-2xl font-bold text-orange-400">Dimensionnement Intelligent</h2>
                    </div>
                    <form id="dimensionnementForm" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-300">Consommation annuelle (kWh)</label>
                                <input type="number" name="consommation" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300">
                            </div>
                            <div>
                                <label class="block text-gray-300">Surface disponible (m²)</label>
                                <input type="number" name="surface_dispo" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300">
                            </div>
                            <div>
                                <label class="block text-gray-300">Type de toiture</label>
                                <select name="type_toiture" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300">
                                    <option value="inclinee">Inclinée</option>
                                    <option value="plate">Plate</option>
                                    <option value="shed">Shed</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-300">Orientation principale</label>
                                <select name="orientation" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300">
                                    <option value="sud">Sud</option>
                                    <option value="sud-est">Sud-Est</option>
                                    <option value="sud-ouest">Sud-Ouest</option>
                                    <option value="est">Est</option>
                                    <option value="ouest">Ouest</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-300">Objectifs spécifiques</label>
                            <textarea name="objectifs" rows="3" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300" placeholder="Ex: autoconsommation maximale, revente totale..."></textarea>
                        </div>
                        <button type="submit" class="w-full px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 flex items-center justify-center">
                            <span class="loading-spinner hidden mr-2">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                            Calculer le dimensionnement
                        </button>
                    </form>
                    <div id="dimensionnementResult" class="mt-4 p-4 bg-gray-700 rounded-md hidden">
                        <pre class="text-gray-300 whitespace-pre-wrap"></pre>
                    </div>
                </div>
            </div>

            <!-- Analyse Production IA -->
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-chart-line text-orange-400 text-2xl mr-3"></i>
                        <h2 class="text-2xl font-bold text-orange-400">Analyse de Production</h2>
                    </div>
                    <form id="analyseForm" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-300">Période d'analyse</label>
                                <select name="periode" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300">
                                    <option value="jour">Journalière</option>
                                    <option value="semaine">Hebdomadaire</option>
                                    <option value="mois">Mensuelle</option>
                                    <option value="annee">Annuelle</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-300">Type d'installation</label>
                                <select name="type_installation" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300">
                                    <option value="residentiel">Résidentiel</option>
                                    <option value="commercial">Commercial</option>
                                    <option value="industriel">Industriel</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-300">Puissance installée (kWc)</label>
                                <input type="number" name="puissance" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300">
                            </div>
                            <div>
                                <label class="block text-gray-300">Stockage batterie</label>
                                <select name="stockage" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300">
                                    <option value="oui">Oui</option>
                                    <option value="non">Non</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-300">Données de production (kWh)</label>
                            <textarea name="donnees" rows="3" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300" placeholder="Entrez vos données de production (une valeur par ligne)..."></textarea>
                        </div>
                        <button type="submit" class="w-full px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 flex items-center justify-center">
                            <span class="loading-spinner hidden mr-2">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                            Analyser les données
                        </button>
                    </form>
                    <div id="analyseResult" class="mt-4 p-4 bg-gray-700 rounded-md hidden">
                        <pre class="text-gray-300 whitespace-pre-wrap"></pre>
                    </div>
                </div>
            </div>

            <!-- Prévisions Météo IA -->
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-cloud-sun text-orange-400 text-2xl mr-3"></i>
                        <h2 class="text-2xl font-bold text-orange-400">Prévisions Météo & Production</h2>
                    </div>
                    <form id="meteoForm" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-300">Ville</label>
                                <input type="text" name="ville" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300" placeholder="Ex: Lomé">
                            </div>
                            <div>
                                <label class="block text-gray-300">Horizon de prévision</label>
                                <select name="horizon" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300">
                                    <option value="24h">24 heures</option>
                                    <option value="48h">48 heures</option>
                                    <option value="7j">7 jours</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-300">Type de données</label>
                                <select name="type_donnees" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300" multiple>
                                    <option value="temperature">Température</option>
                                    <option value="ensoleillement">Ensoleillement</option>
                                    <option value="nuages">Couverture nuageuse</option>
                                    <option value="precipitation">Précipitations</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-300">Installation existante</label>
                                <select name="installation" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300">
                                    <option value="oui">Oui</option>
                                    <option value="non">Non</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="w-full px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 flex items-center justify-center">
                            <span class="loading-spinner hidden mr-2">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                            Obtenir les prévisions
                        </button>
                    </form>                    <div id="meteoResult" class="mt-4 p-4 bg-gray-700 rounded-md hidden overflow-auto max-h-96">
                        <h3 class="text-xl font-semibold text-orange-400 mb-3">Résultats de l'analyse</h3>
                        <div class="text-gray-300 whitespace-pre-wrap break-words"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showLoading(formId) {
    const form = document.getElementById(formId);
    const spinner = form.querySelector('.loading-spinner');
    const button = form.querySelector('button');
    spinner.classList.remove('hidden');
    button.disabled = true;
}

function hideLoading(formId) {
    const form = document.getElementById(formId);
    const spinner = form.querySelector('.loading-spinner');
    const button = form.querySelector('button');
    spinner.classList.add('hidden');
    button.disabled = false;
}

function showResult(resultId, content, isError = false) {
    const resultDiv = document.getElementById(resultId);
    const contentDiv = resultDiv.querySelector('div.text-gray-300');
    
    if (isError) {
        contentDiv.innerHTML = `<div class="text-red-400">${content}</div>`;
    } else {
        // Déterminer le type de contenu en fonction de l'ID du résultat
        switch (resultId) {
            case 'meteoResult':
                contentDiv.innerHTML = `<div class="space-y-4">${formatMeteoContent(content)}</div>`;
                break;
            case 'devisResult':
                contentDiv.innerHTML = formatDevisContent(content);
                break;
            case 'dimensionnementResult':
                contentDiv.innerHTML = formatDimensionnementContent(content);
                break;
            case 'analyseResult':
                contentDiv.innerHTML = formatAnalyseContent(content);
                break;
            default:
                contentDiv.innerHTML = `<div class="space-y-4">${content}</div>`;
        }
    }
    
    resultDiv.classList.remove('hidden');
}

function formatMeteoContent(content) {
    // Si c'est un objet avec donnees_meteo
    if (typeof content === 'object' && content.donnees_meteo) {
        const meteo = content.donnees_meteo;
        let html = `<div class="bg-gray-600 p-4 rounded-lg">
            <h4 class="text-lg font-semibold text-orange-400 mb-2">Prévisions pour ${meteo.ville}, ${meteo.pays}</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">`;
        
        meteo.previsions.forEach(prev => {
            html += `
                <div class="bg-gray-700 p-3 rounded-lg">
                    <div class="font-medium text-orange-300">${formatDate(prev.date)}</div>
                    <div class="grid grid-cols-2 gap-2 mt-2 text-sm">
                        <div>Température: ${prev.temperature}°C</div>
                        <div>Humidité: ${prev.humidite}%</div>
                        <div>Nuages: ${prev.nuages}%</div>
                        <div>Vent: ${prev.vent.vitesse} km/h</div>
                    </div>
                </div>`;
        });
        
        html += `</div></div>`;
        return html;
    }
    
    // Format texte par défaut
    return content.replace(/\n/g, '<br>');
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleString('fr-FR', { 
        day: '2-digit',
        month: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function formatDevisContent(content) {
    const devisText = typeof content === 'object' ? content.devis : content;
    if (!devisText) return 'Aucun résultat disponible';
    
    return `<div class="bg-gray-600 p-4 rounded-lg space-y-3">
        <h4 class="text-lg font-semibold text-orange-400">Proposition de devis</h4>
        <div class="whitespace-pre-wrap">${devisText.replace(/\n/g, '<br>')}</div>
    </div>`;
}

function formatDimensionnementContent(content) {
    const dimensionnementText = typeof content === 'object' ? content.dimensionnement : content;
    if (!dimensionnementText) return 'Aucun résultat disponible';
    
    return `<div class="bg-gray-600 p-4 rounded-lg space-y-3">
        <h4 class="text-lg font-semibold text-orange-400">Dimensionnement proposé</h4>
        <div class="whitespace-pre-wrap">${dimensionnementText.replace(/\n/g, '<br>')}</div>
    </div>`;
}

function formatAnalyseContent(content) {
    const analyseText = typeof content === 'object' ? content.analyse : content;
    if (!analyseText) return 'Aucun résultat disponible';
    
    return `<div class="bg-gray-600 p-4 rounded-lg space-y-3">
        <h4 class="text-lg font-semibold text-orange-400">Résultats de l'analyse</h4>
        <div class="whitespace-pre-wrap">${analyseText.replace(/\n/g, '<br>')}</div>
    </div>`;
}

document.getElementById('devisForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    showLoading('devisForm');
    
    const formData = {
        type_propriete: e.target.type_propriete.value,
        surface: e.target.surface.value,
        budget: e.target.budget.value,
        region: e.target.region.value,
        description: e.target.description.value
    };
    
    try {
        const response = await fetch('/api/devis-ia', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-API-KEY': 'ne-don-energy-api-key'
            },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        if (data.success) {
            showResult('devisResult', data);
        } else {
            showResult('devisResult', data.message || 'Une erreur est survenue', true);
        }
    } catch (error) {
        console.error('Erreur:', error);
        showResult('devisResult', 'Erreur lors de la génération du devis', true);
    } finally {
        hideLoading('devisForm');
    }
});

document.getElementById('dimensionnementForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    showLoading('dimensionnementForm');
    
    const formData = {
        consommation: e.target.consommation.value,
        surface_dispo: e.target.surface_dispo.value,
        type_toiture: e.target.type_toiture.value,
        orientation: e.target.orientation.value,
        objectifs: e.target.objectifs.value
    };
    
    try {                const response = await fetch('/api/dimensionnement-ia', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-API-KEY': 'ne-don-energy-api-key'
            },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        if (data.success) {
            showResult('dimensionnementResult', data);
        } else {
            showResult('dimensionnementResult', data.message || 'Une erreur est survenue', true);
        }
    } catch (error) {
        console.error('Erreur:', error);
        showResult('dimensionnementResult', 'Erreur lors du calcul du dimensionnement', true);
    } finally {
        hideLoading('dimensionnementForm');
    }
});

document.getElementById('analyseForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    showLoading('analyseForm');
    
    const formData = {
        periode: e.target.periode.value,
        type_installation: e.target.type_installation.value,
        puissance: e.target.puissance.value,
        stockage: e.target.stockage.value,
        donnees: e.target.donnees.value
    };
    
    try {
        const response = await fetch('/api/analyse-production-ia', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-API-KEY': 'ne-don-energy-api-key'
            },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        if (data.success) {
            showResult('analyseResult', data);
        } else {
            showResult('analyseResult', data.message || 'Une erreur est survenue', true);
        }
    } catch (error) {
        console.error('Erreur:', error);
        showResult('analyseResult', 'Erreur lors de l\'analyse des données', true);
    } finally {
        hideLoading('analyseForm');
    }
});

document.getElementById('meteoForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    showLoading('meteoForm');    const formData = {
        ville: e.target.ville.value,
        horizon: e.target.horizon.value,
        type_donnees: Array.from(e.target.type_donnees.selectedOptions).map(opt => opt.value),
        installation: e.target.installation.value
    };
    
    try {
        const response = await fetch('/api/meteo-ia', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-API-KEY': 'ne-don-energy-api-key'
            },
            body: JSON.stringify(formData)
        });
          const data = await response.json();
        if (data.success) {
            showResult('meteoResult', {
                donnees_meteo: data.donnees_meteo,
                analyse: data.analyse
            });
        } else {
            showResult('meteoResult', data.message || 'Une erreur est survenue', true);
        }
    } catch (error) {
        showResult('meteoResult', 'Erreur lors de la récupération des prévisions météo', true);
    } finally {
        hideLoading('meteoForm');
    }
});
</script>
@endpush

@extends('layouts.technicien')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gestion des Onduleurs</h1>
            <p class="text-gray-600 mt-1">Supervisez et gérez tous les onduleurs de vos installations</p>
        </div>
        <div class="flex space-x-4">
            <button id="refreshStatus" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                <i class="fas fa-sync-alt mr-2"></i>
                Actualiser les statuts
            </button>
            <a href="{{ route('technicien.onduleurs.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>
                Ajouter un onduleur
            </a>
        </div>
    </div>

    @if($onduleurs->isEmpty())
        <div class="bg-white shadow-md rounded-lg p-6 text-center">
            <div class="mb-4">
                <i class="fas fa-solar-panel text-gray-400 text-5xl"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-700 mb-2">Aucun onduleur trouvé</h2>
            <p class="text-gray-600 mb-4">Vous n'avez pas encore ajouté d'onduleurs à votre installation.</p>
            <a href="{{ route('technicien.onduleurs.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>
                Ajouter votre premier onduleur
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6">
            @foreach($onduleurs as $onduleur)
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $onduleur->installation->nom }}</h3>
                                <span class="status-indicator ml-3" data-onduleur-id="{{ $onduleur->id }}">
                                    <div class="animate-pulse inline-flex h-3 w-3 rounded-full bg-gray-400 mr-2"></div>
                                    <span class="text-sm">Vérification...</span>
                                </span>
                            </div>
                            <p class="text-gray-600 mt-1">{{ $onduleur->marque }} - {{ $onduleur->modele }}</p>
                            <p class="text-gray-500 text-sm mt-1">N° Série: {{ $onduleur->numero_serie }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('technicien.onduleurs.show', $onduleur) }}" 
                               class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                <i class="fas fa-chart-line mr-1"></i>
                                Données en direct
                            </a>
                            <a href="{{ route('technicien.onduleurs.edit', $onduleur) }}" 
                               class="inline-flex items-center px-3 py-1 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600">
                                <i class="fas fa-cog mr-1"></i>
                                Paramètres
                            </a>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-500">Puissance actuelle</div>
                            <div class="text-xl font-semibold text-gray-800" id="power-{{ $onduleur->id }}">-- W</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-500">Production du jour</div>
                            <div class="text-xl font-semibold text-gray-800" id="daily-{{ $onduleur->id }}">-- kWh</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-500">Température</div>
                            <div class="text-xl font-semibold text-gray-800" id="temp-{{ $onduleur->id }}">-- °C</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-500">Efficacité</div>
                            <div class="text-xl font-semibold text-gray-800" id="efficiency-{{ $onduleur->id }}">-- %</div>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-between border-t pt-4">
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            IP: {{ $onduleur->ip_address }}:{{ $onduleur->port }}
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="testConnection('{{ $onduleur->id }}')" class="text-sm text-blue-600 hover:text-blue-800">
                                <i class="fas fa-plug mr-1"></i>
                                Tester la connexion
                            </button>
                            <button onclick="resetConnection('{{ $onduleur->id }}')" class="text-sm text-yellow-600 hover:text-yellow-800">
                                <i class="fas fa-redo mr-1"></i>
                                Réinitialiser
                            </button>
                            <button onclick="confirmDelete('{{ $onduleur->id }}')" class="text-sm text-red-600 hover:text-red-800">
                                <i class="fas fa-trash mr-1"></i>
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour mettre à jour le statut d'un onduleur
    function updateOnduleurStatus(onduleurId) {
        fetch(`/technicien/onduleurs/${onduleurId}/check-connection`)
            .then(response => response.json())
            .then(data => {
                const indicator = document.querySelector(`.status-indicator[data-onduleur-id="${onduleurId}"]`);
                const color = data.connected ? 'bg-green-500' : 'bg-red-500';
                const status = data.connected ? 'Connecté' : 'Déconnecté';
                const textColor = data.connected ? 'text-green-600' : 'text-red-600';
                
                indicator.innerHTML = `
                    <div class="inline-flex h-3 w-3 rounded-full ${color} mr-2"></div>
                    <span class="text-sm ${textColor}">${status}</span>
                `;

                // Mise à jour des données en temps réel si connecté
                if (data.connected && data.metrics) {
                    document.getElementById(`power-${onduleurId}`).textContent = `${data.metrics.power} W`;
                    document.getElementById(`daily-${onduleurId}`).textContent = `${data.metrics.daily_energy} kWh`;
                    document.getElementById(`temp-${onduleurId}`).textContent = `${data.metrics.temperature} °C`;
                    document.getElementById(`efficiency-${onduleurId}`).textContent = `${data.metrics.efficiency}%`;
                }
            })
            .catch(() => {
                const indicator = document.querySelector(`.status-indicator[data-onduleur-id="${onduleurId}"]`);
                indicator.innerHTML = `
                    <div class="inline-flex h-3 w-3 rounded-full bg-red-500 mr-2"></div>
                    <span class="text-sm text-red-600">Erreur</span>
                `;
            });
    }

    // Mettre à jour le statut de tous les onduleurs
    function updateAllStatuses() {
        document.querySelectorAll('.status-indicator').forEach(indicator => {
            const onduleurId = indicator.dataset.onduleurId;
            updateOnduleurStatus(onduleurId);
        });
    }

    // Actualisation automatique toutes les 30 secondes
    updateAllStatuses();
    setInterval(updateAllStatuses, 30000);

    // Bouton d'actualisation manuelle
    document.getElementById('refreshStatus').addEventListener('click', updateAllStatuses);
});

// Fonctions pour les actions
function testConnection(onduleurId) {
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = `<i class="fas fa-spinner fa-spin mr-1"></i>Test en cours...`;

    fetch(`/technicien/onduleurs/${onduleurId}/test-connection`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Test de connexion réussi !');
            updateOnduleurStatus(onduleurId);
        } else {
            alert('Échec du test de connexion : ' + data.message);
        }
    })
    .catch(error => {
        alert('Erreur lors du test de connexion');
        console.error('Erreur:', error);
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function resetConnection(onduleurId) {
    if (confirm('Voulez-vous vraiment réinitialiser la connexion de cet onduleur ?')) {
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = `<i class="fas fa-spinner fa-spin mr-1"></i>Réinitialisation...`;

        fetch(`/technicien/onduleurs/${onduleurId}/reset-connection`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Connexion réinitialisée avec succès !');
                updateOnduleurStatus(onduleurId);
            } else {
                alert('Échec de la réinitialisation : ' + data.message);
            }
        })
        .catch(error => {
            alert('Erreur lors de la réinitialisation');
            console.error('Erreur:', error);
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = originalText;
        });
    }
}

function confirmDelete(onduleurId) {
    if (confirm('Voulez-vous vraiment supprimer cet onduleur ? Cette action est irréversible.')) {
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = `<i class="fas fa-spinner fa-spin mr-1"></i>Suppression...`;

        fetch(`/technicien/onduleurs/${onduleurId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Retirer l'élément de l'interface
                const onduleurElement = button.closest('.bg-white.shadow-md');
                onduleurElement.remove();
                
                // Si c'était le dernier onduleur, afficher le message "aucun onduleur"
                const remainingOnduleurs = document.querySelectorAll('.bg-white.shadow-md');
                if (remainingOnduleurs.length === 0) {
                    location.reload();
                }
                
                alert('Onduleur supprimé avec succès !');
            } else {
                alert('Échec de la suppression : ' + data.message);
            }
        })
        .catch(error => {
            alert('Erreur lors de la suppression');
            console.error('Erreur:', error);
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = originalText;
        });
    }
}
</script>
@endpush
@endsection
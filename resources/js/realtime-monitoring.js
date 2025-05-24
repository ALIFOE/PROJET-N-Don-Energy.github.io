import Chart from 'chart.js/auto';

// Fonction pour récupérer les données de l'API Laravel
async function fetchRealtimeData() {
    try {
        // Vérifier si le token CSRF est disponible
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            console.error('Token CSRF non trouvé dans le document');
        }

        const response = await fetch('/api/admin/realtime-production', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            const errorData = await response.json().catch(() => null);
            throw new Error(
                `Erreur HTTP ${response.status}: ${response.statusText}\n` +
                `Message: ${errorData?.message || 'Aucun détail disponible'}`
            );
        }

        return await response.json();
    } catch (error) {
        console.error('Erreur détaillée lors de la récupération des données:', {
            message: error.message,
            stack: error.stack,
            token: !!document.querySelector('meta[name="csrf-token"]')
        });
        return {
            labels: [],
            production: [],
            consommation: []
        };
    }
}

// Fonction pour afficher le graphique
async function renderRealtimeChart() {
    const data = await fetchRealtimeData();
    const ctx = document.getElementById('realtimeChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Production (Wh)',
                    data: data.production,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                },
                {
                    label: 'Consommation (Wh)',
                    data: data.consommation,
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Production & Consommation en temps réel' }
            }
        }
    });
}

// Appel de la fonction au chargement de la page
window.addEventListener('DOMContentLoaded', renderRealtimeChart);

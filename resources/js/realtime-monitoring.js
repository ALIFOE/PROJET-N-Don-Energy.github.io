import Chart from 'chart.js/auto';

// Fonction pour récupérer les données de l'API Laravel
async function fetchRealtimeData() {
    const response = await fetch('/api/realtime-production');
    return await response.json();
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

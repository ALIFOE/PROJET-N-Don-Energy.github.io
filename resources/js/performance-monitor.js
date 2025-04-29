class PerformanceMonitor {
    constructor() {
        this.updateInterval = 30000; // Mise à jour toutes les 30 secondes
        this.initialize();
    }

    initialize() {
        this.updateData();
        setInterval(() => this.updateData(), this.updateInterval);
    }

    async updateData() {
        try {
            const response = await fetch('/api/performances');
            const data = await response.json();
            
            this.updateInverterData(data.inverter);
            this.updateRegionalData(data.regional);
        } catch (error) {
            console.error('Erreur lors de la mise à jour des données:', error);
        }
    }

    updateInverterData(data) {
        // Mise à jour des données de l'onduleur
        document.getElementById('production-actuelle').textContent = `${data.production_actuelle} kW`;
        document.getElementById('production-journaliere').textContent = `${data.production_journaliere} kWh`;
        document.getElementById('etat-onduleur').textContent = data.etat;

        // Mise à jour des classes de statut
        const etatElement = document.getElementById('etat-onduleur');
        etatElement.className = 'text-3xl font-bold mb-2';
        switch (data.etat) {
            case 'Normal':
                etatElement.classList.add('text-green-600');
                break;
            case 'Avertissement':
                etatElement.classList.add('text-yellow-600');
                break;
            case 'Erreur':
                etatElement.classList.add('text-red-600');
                break;
            default:
                etatElement.classList.add('text-gray-600');
        }
    }

    updateRegionalData(data) {
        // Mise à jour des données régionales
        document.getElementById('production-regionale').textContent = `${data.production_totale} MWh`;
        document.getElementById('irradiation').textContent = `${data.irradiation} kWh/m²`;
        document.getElementById('performance-collective').textContent = `${Math.round(data.performance_collective)}%`;
    }
}

// Initialisation du moniteur de performance
document.addEventListener('DOMContentLoaded', () => {
    new PerformanceMonitor();
});
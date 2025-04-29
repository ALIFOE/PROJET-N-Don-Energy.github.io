document.addEventListener('DOMContentLoaded', function() {
    const brandSelect = document.getElementById('brand');
    const modelSelect = document.getElementById('model');
    
    // Liste des modèles par marque
    const inverterModels = {
        'sma': [ 
            'Sunny Boy 3.0',
            'Sunny Boy 3.6',
            'Sunny Boy 4.0',
            'Sunny Boy 5.0',
            'Sunny Boy 6.0',
            'Sunny Tripower 8.0',
            'Sunny Tripower 10.0'
        ],
        'fronius': [
            'Primo 3.0-1',
            'Primo 3.6-1',
            'Primo 4.0-1',
            'Primo 4.6-1',
            'Primo 5.0-1',
            'Primo 6.0-1',
            'Symo 10.0-3-M'
        ],
        'huawei': [
            'SUN2000-3KTL-M0',
            'SUN2000-4KTL-M0',
            'SUN2000-5KTL-M0',
            'SUN2000-6KTL-M0',
            'SUN2000-8KTL-M0',
            'SUN2000-10KTL-M0'
        ],
        'solaredge': [
            'SE3K-RWS',
            'SE4K-RWS',
            'SE5K-RWS',
            'SE6K-RWS',
            'SE8K-RWS',
            'SE10K-RWS'
        ],
        'growatt': [
            'MIN 3000TL-X',
            'MIN 4000TL-X',
            'MIN 5000TL-X',
            'MIN 6000TL-X',
            'MOD 8000TL3-X',
            'MOD 10000TL3-X'
        ],
        'goodwe': [
            'GW3000-NS',
            'GW3600-NS',
            'GW4200-NS',
            'GW5000-NS',
            'GW6000-NS',
            'GW8K-DT',
            'GW10K-DT'
        ]
    };

    // Gestionnaire d'événement pour le changement de marque
    brandSelect.addEventListener('change', function() {
        const selectedBrand = this.value;
        modelSelect.innerHTML = ''; // Vider la liste des modèles

        if (selectedBrand) {
            // Activer le select des modèles
            modelSelect.disabled = false;
            
            // Ajouter l'option par défaut
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Sélectionner un modèle';
            modelSelect.appendChild(defaultOption);
            
            // Ajouter les modèles correspondants à la marque
            inverterModels[selectedBrand].forEach(model => {
                const option = document.createElement('option');
                option.value = model.toLowerCase().replace(/\s+/g, '-');
                option.textContent = model;
                modelSelect.appendChild(option);
            });
        } else {
            // Désactiver le select des modèles si aucune marque n'est sélectionnée
            modelSelect.disabled = true;
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Sélectionner d\'abord une marque';
            modelSelect.appendChild(defaultOption);
        }
    });
});
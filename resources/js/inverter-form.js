document.addEventListener('DOMContentLoaded', function() {
    const brandSelect = document.getElementById('brand');
    const modelSelect = document.getElementById('model');

    if (brandSelect && modelSelect) {
        brandSelect.addEventListener('change', function() {
            const brand = this.value;
            if (brand) {
                // Vider et désactiver le select des modèles pendant le chargement
                modelSelect.innerHTML = '<option value="">Chargement...</option>';
                modelSelect.disabled = true;

                // Appeler l'API pour obtenir les modèles
                fetch(`/api/inverter-models/${brand}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remplir le select des modèles
                            modelSelect.innerHTML = '<option value="">Sélectionner un modèle</option>';
                            Object.entries(data.models).forEach(([value, label]) => {
                                
                                const option = document.createElement('option');
                                option.value = value;
                                option.textContent = label;
                                modelSelect.appendChild(option);
                            });
                            modelSelect.disabled = false;
                        } else {
                            modelSelect.innerHTML = '<option value="">Erreur de chargement des modèles</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        modelSelect.innerHTML = '<option value="">Erreur de chargement des modèles</option>';
                    });
            } else {
                // Si aucune marque n'est sélectionnée, vider le select des modèles
                modelSelect.innerHTML = '<option value="">Sélectionner d\'abord une marque</option>';
                modelSelect.disabled = true;
            }
        });
    }
});
@extends('layouts.app')

@section('content')
<!-- Ajout du meta tag CSRF -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6">Ajouter un nouvel onduleur</h2>

        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-2">Recherche automatique d'onduleurs sur le réseau</h3>
            <p class="text-sm text-gray-600 mb-4">Cliquez sur le bouton ci-dessous pour rechercher des onduleurs connectés à votre réseau local. Sélectionnez-en un pour remplir automatiquement les informations ci-dessous.</p>
            <p class="text-sm text-gray-600 mb-4">Assurez-vous que votre appareil est connecté au même réseau que l'onduleur.</p>
            <button id="scanButton" type="button" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded flex items-center">
                <span>Recherche automatique des onduleurs</span>
                <span id="loadingSpinner" class="ml-2 hidden">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>

            <div id="scanStatus" class="mt-3 text-sm hidden">
                <p id="scanStatusText" class="font-medium"></p>
            </div>

            <div id="scanResults" class="mt-4 hidden">
                <h3 class="text-lg font-semibold mb-2">Onduleurs détectés</h3>
                <div id="resultsList" class="space-y-2">
                    <!-- Les résultats seront ajoutés ici dynamiquement -->
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('onduleurs.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="user_id" value="{{ Auth::id() }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="marque" class="block text-sm font-medium text-gray-700">Marque</label>
                    <select name="marque" id="marque" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Sélectionner une marque</option>
                        <option value="sma">SMA</option>
                        <option value="fronius">Fronius</option>
                        <option value="huawei">Huawei</option>
                        <option value="solaredge">SolarEdge</option>
                        <option value="goodwe">GoodWe</option>
                        <option value="growatt">Growatt</option>
                        <option value="sungrow">Sungrow</option>
                        <option value="enphase">Enphase</option>
                    </select>
                </div>

                <div>
                    <label for="modele" class="block text-sm font-medium text-gray-700">Modèle</label>
                    <select name="modele" id="modele" required disabled class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Sélectionner d'abord une marque</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="numero_serie" class="block text-sm font-medium text-gray-700">Numéro de série</label>
                    <input type="text" name="numero_serie" id="numero_serie" required 
                        value="{{ old('numero_serie') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="md:col-span-2">
                    <label for="ip_address" class="block text-sm font-medium text-gray-700">Adresse IP</label>
                    <input type="text" name="ip_address" id="ip_address" 
                        pattern="^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$"
                        placeholder="ex: 192.168.1.100"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="mt-1 text-sm text-gray-500">L'adresse IP de l'onduleur sur votre réseau local</p>
                </div>

                <div>
                    <label for="port" class="block text-sm font-medium text-gray-700">Port</label>
                    <input type="number" name="port" id="port" 
                        min="1" max="65535" placeholder="502"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="mt-1 text-sm text-gray-500">Port par défaut : 502 (Modbus TCP)</p>
                </div>

                <div>
                    <label for="protocole" class="block text-sm font-medium text-gray-700">Protocole</label>
                    <select name="protocole" id="protocole" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="modbus_tcp">Modbus TCP</option>
                        <option value="sunspec">SunSpec</option>
                        <option value="rest_api">REST API</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Authentification</label>
                    <div class="mt-2 space-y-4">
                        <div>
                            <label for="username" class="block text-sm text-gray-600">Nom d'utilisateur</label>
                            <input type="text" name="username" id="username"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="password" class="block text-sm text-gray-600">Mot de passe</label>
                            <input type="password" name="password" id="password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="est_connecte" id="est_connecte" value="1" checked
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="est_connecte" class="ml-2 block text-sm text-gray-900">
                            Connecter l'onduleur après l'ajout
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <button type="button" id="testConnectionButton" 
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    Tester la connexion
                </button>
                <div id="connectionStatus" class="mt-2 hidden">
                    <p class="text-sm font-medium">
                        <span id="connectionStatusIcon" class="inline-block w-4 h-4 rounded-full mr-2"></span>
                        <span id="connectionStatusText"></span>
                    </p>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8">
                <button type="button" onclick="window.history.back()" 
                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                    Annuler
                </button>
                <button type="submit" 
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    Ajouter l'onduleur
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const brandSelect = document.getElementById('marque');
    const modelSelect = document.getElementById('modele');
    const protocolSelect = document.getElementById('protocole');
    
    const inverterModels = {
        'sma': [
            'Sunny Boy 3.0', 'Sunny Boy 3.6', 'Sunny Boy 4.0', 'Sunny Boy 5.0', 'Sunny Boy 6.0',
            'Sunny Tripower 5.0', 'Sunny Tripower 6.0', 'Sunny Tripower 8.0', 'Sunny Tripower 10.0'
        ],
        'fronius': [
            'Primo 3.0-1', 'Primo 3.6-1', 'Primo 4.0-1', 'Primo 4.6-1', 'Primo 5.0-1',
            'Symo 3.0-3-M', 'Symo 4.5-3-M', 'Symo 5.0-3-M', 'Symo 7.0-3-M', 'Symo 8.2-3-M', 'Symo 10.0-3-M'
        ],
        'solaredge': [
            'SE3000H', 'SE3680H', 'SE4000H', 'SE5000H', 'SE6000H', 'SE7600H', 'SE8000H', 'SE10000H'
        ],
        'huawei': [
            'SUN2000-3KTL-M0', 'SUN2000-4KTL-M0', 'SUN2000-5KTL-M0', 'SUN2000-6KTL-M0',
            'SUN2000-8KTL-M0', 'SUN2000-10KTL-M0', 'SUN2000-12KTL-M0', 'SUN2000-15KTL-M0'
        ],
        'goodwe': [
            'GW3000D-NS', 'GW3600D-NS', 'GW4200D-NS', 'GW5000D-NS', 'GW6000D-NS', 'GW8000D-NS', 'GW10000D-NS'
        ],
        'growatt': [
            'MIN 3000TL-X', 'MIN 4000TL-X', 'MIN 5000TL-X', 'MIN 6000TL-X',
            'MOD 8000TL3-X', 'MOD 10000TL3-X'
        ],
        'sungrow': [
            'SG3.0RT', 'SG4.0RT', 'SG5.0RT', 'SG6.0RT', 'SG8.0RT', 'SG10.0RT'
        ],
        'enphase': [
            'IQ7', 'IQ7+', 'IQ8', 'IQ8+'
        ]
    };

    const brandProtocols = {
        'sma': 'modbus_tcp',
        'fronius': 'rest_api',
        'solaredge': 'rest_api',
        'huawei': 'modbus_tcp',
        'goodwe': 'modbus_tcp',
        'growatt': 'modbus_tcp',
        'sungrow': 'modbus_tcp',
        'enphase': 'rest_api'
    };

    brandSelect.addEventListener('change', function() {
        const selectedBrand = this.value;
        modelSelect.innerHTML = '';
        
        if (selectedBrand) {
            modelSelect.disabled = false;
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Sélectionner un modèle';
            modelSelect.appendChild(defaultOption);
            
            inverterModels[selectedBrand].forEach(model => {
                const option = document.createElement('option');
                option.value = model;
                option.textContent = model;
                modelSelect.appendChild(option);
            });

            // Mettre à jour le protocole recommandé
            protocolSelect.value = brandProtocols[selectedBrand] || 'modbus_tcp';
        } else {
            modelSelect.disabled = true;
            modelSelect.innerHTML = '<option value="">Sélectionner d\'abord une marque</option>';
        }
    });

    // Code existant pour la recherche d'onduleurs
    const scanButton = document.getElementById('scanButton');
    const scanResults = document.getElementById('scanResults');
    const resultsList = document.getElementById('resultsList');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const scanStatus = document.getElementById('scanStatus');
    const scanStatusText = document.getElementById('scanStatusText');
    
    scanButton.addEventListener('click', function() {
        scanButton.disabled = true;
        loadingSpinner.classList.remove('hidden');
        scanStatus.classList.remove('hidden');
        scanStatusText.textContent = 'Recherche en cours...';
        
        fetch('/api/onduleurs/scan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            scanResults.classList.remove('hidden');
            resultsList.innerHTML = '';
            
            if (data.status === 'error') {
                throw new Error(data.message || 'Une erreur est survenue');
            }
            
            if (data.status === 'empty' || !data.data || data.data.length === 0) {
                resultsList.innerHTML = '<p class="text-gray-500">Aucun onduleur trouvé sur le réseau</p>';
                scanStatusText.textContent = 'Aucun onduleur trouvé';
                scanStatusText.className = 'text-yellow-500';
                return;
            }
            
            data.data.forEach(inverter => {
                const div = document.createElement('div');
                div.className = 'p-3 bg-gray-50 rounded border border-gray-200';
                div.innerHTML = `
                    <p class="font-medium">${inverter.brand} ${inverter.model}</p>
                    <p class="text-sm text-gray-600">IP: ${inverter.ip}:${inverter.port}</p>
                    <p class="text-sm text-gray-600">N° série: ${inverter.serial_number}</p>
                    <p class="text-sm text-gray-600">Protocole: ${inverter.connection_type}</p>
                    <button type="button" class="mt-2 bg-indigo-500 hover:bg-indigo-600 text-white text-sm py-1 px-3 rounded"
                        onclick="selectInverter('${inverter.brand}', '${inverter.model}', '${inverter.serial_number}', '${inverter.ip}', '${inverter.port}')">
                        Sélectionner
                    </button>
                `;
                resultsList.appendChild(div);
            });
            
            scanStatusText.textContent = 'Recherche terminée avec succès';
            scanStatusText.className = 'text-green-500';
        })
        .catch(error => {
            console.error('Erreur:', error);
            
            let errorMessage;
            if (error.message.includes('HTTP: 500')) {
                errorMessage = 'Erreur serveur lors de la recherche. Veuillez réessayer.';
            } else if (error.message.includes('HTTP: 404')) {
                errorMessage = 'Aucun onduleur trouvé sur le réseau.';
            } else if (error.message.includes('Failed to fetch')) {
                errorMessage = 'Impossible de contacter le serveur. Vérifiez votre connexion.';
            } else {
                errorMessage = 'Erreur lors de la recherche: ' + error.message;
            }
            
            resultsList.innerHTML = `<p class="text-red-500">${errorMessage}</p>`;
            scanStatusText.textContent = errorMessage;
            scanStatusText.className = 'text-red-500';
            
            // Afficher des conseils de dépannage
            const troubleshootingDiv = document.createElement('div');
            troubleshootingDiv.className = 'mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded';
            troubleshootingDiv.innerHTML = `
                <h4 class="font-semibold text-yellow-800 mb-2">Conseils de dépannage :</h4>
                <ul class="list-disc pl-5 text-sm text-yellow-700 space-y-1">
                    <li>Vérifiez que l'onduleur est sous tension et connecté au réseau</li>
                    <li>Assurez-vous que votre ordinateur est sur le même réseau que l'onduleur</li>
                    <li>Vérifiez que les ports nécessaires (502, 1502, 80, 8080) ne sont pas bloqués par un pare-feu</li>
                    <li>Essayez de désactiver temporairement votre pare-feu pour tester</li>
                    <li>Si le problème persiste, contactez le support technique</li>
                </ul>
            `;
            resultsList.appendChild(troubleshootingDiv);
        })
        .finally(() => {
            scanButton.disabled = false;
            loadingSpinner.classList.add('hidden');
        });
    });

    // Code pour tester la connexion
    const testConnectionButton = document.getElementById('testConnectionButton');
    const connectionStatus = document.getElementById('connectionStatus');
    const connectionStatusIcon = document.getElementById('connectionStatusIcon');
    const connectionStatusText = document.getElementById('connectionStatusText');

    testConnectionButton.addEventListener('click', function() {
        const ip = document.getElementById('ip_address').value;
        const port = document.getElementById('port').value;
        const protocol = document.getElementById('protocole').value;

        if (!ip || !port || !protocol) {
            alert('Veuillez remplir tous les champs requis (Adresse IP, Port et Protocole).');
            return;
        }

        // Validation de l'adresse IP
        const ipRegex = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
        if (!ipRegex.test(ip)) {
            alert('Veuillez entrer une adresse IP valide.');
            return;
        }

        // Validation du port
        if (port < 1 || port > 65535) {
            alert('Le port doit être compris entre 1 et 65535.');
            return;
        }

        testConnectionButton.disabled = true;
        testConnectionButton.textContent = 'Test en cours...';
        
        fetch('/onduleurs/test-connection', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                ip: ip,
                port: parseInt(port),
                protocol: protocol.toLowerCase()
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau ou serveur');
            }
            return response.json();
        })
        .then(data => {
            connectionStatus.classList.remove('hidden');
            if (data.success) {
                connectionStatusIcon.className = 'inline-block w-4 h-4 rounded-full bg-green-500 mr-2';
                connectionStatusText.textContent = 'Connexion réussie';
                connectionStatusText.className = 'text-green-500';
            } else {
                connectionStatusIcon.className = 'inline-block w-4 h-4 rounded-full bg-red-500 mr-2';
                connectionStatusText.textContent = data.message;
                connectionStatusText.className = 'text-red-500';
                
                // Afficher des instructions supplémentaires si c'est un problème de pare-feu
                if (data.error_code && (data.error_code === 10060 || data.error_code === 10061)) {
                    // Vérifier si le message d'aide existe déjà
                    const existingHelpText = connectionStatus.querySelector('.help-text');
                    if (!existingHelpText) {
                        const helpText = document.createElement('div');
                        helpText.className = 'mt-2 text-sm text-gray-600 help-text';
                        helpText.innerHTML = `
                            <p class="font-semibold">Comment résoudre ce problème :</p>
                            <ul class="list-disc pl-5 mt-1">
                                <li>Vérifiez que le pare-feu Windows autorise les connexions sur le port ${port}</li>
                                <li>Si vous utilisez un antivirus, vérifiez ses paramètres de pare-feu</li>
                                <li>Assurez-vous que l'onduleur n'est pas bloqué par un pare-feu réseau</li>
                                <li>Vérifiez que l'onduleur est bien sous tension et connecté au réseau</li>
                            </ul>
                        `;
                        connectionStatus.appendChild(helpText);
                    }
                }
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            connectionStatus.classList.remove('hidden');
            connectionStatusIcon.className = 'inline-block w-4 h-4 rounded-full bg-red-500 mr-2';
            connectionStatusText.textContent = 'Erreur lors du test de connexion: ' + error.message;
            connectionStatusText.className = 'text-red-500';
        })
        .finally(() => {
            testConnectionButton.disabled = false;
            testConnectionButton.textContent = 'Tester la connexion';
        });
    });

    // Gestion de la soumission du formulaire
    const form = document.querySelector('form');
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Ajout en cours...';
        
        if (!document.getElementById('est_connecte').checked) {
            form.submit();
            return;
        }

        try {
            const ip = document.getElementById('ip_address').value;
            const port = document.getElementById('port').value;
            const protocol = document.getElementById('protocole').value;

            // Test de connexion avant soumission
            const response = await fetch('/onduleurs/test-connection', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ ip, port, protocol })
            });

            const data = await response.json();

            if (data.success) {
                // Afficher un message de succès
                const successMessage = document.createElement('div');
                successMessage.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded shadow-lg';
                successMessage.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Connexion réussie ! Ajout de l'onduleur...</span>
                    </div>
                `;
                document.body.appendChild(successMessage);
                
                // Soumettre le formulaire après un court délai
                setTimeout(() => {
                    form.submit();
                }, 1500);
            } else {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
                alert('La connexion à l\'onduleur a échoué. Veuillez vérifier les paramètres de connexion et réessayer.');
            }
        } catch (error) {
            console.error('Erreur:', error);
            submitButton.disabled = false;
            submitButton.textContent = originalText;
            alert('Une erreur est survenue lors de la tentative de connexion.');
        }
    });
});

function selectInverter(brand, model, serialNumber, ip, port) {
    document.getElementById('marque').value = brand.toLowerCase();
    document.getElementById('marque').dispatchEvent(new Event('change'));
    document.getElementById('modele').value = model;
    document.getElementById('numero_serie').value = serialNumber;
    document.getElementById('ip_address').value = ip;
    document.getElementById('port').value = port;
    document.getElementById('est_connecte').checked = true;
}
</script>
@endpush
@endsection
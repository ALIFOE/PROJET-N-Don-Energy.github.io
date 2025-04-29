<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Menu latéral -->
                <div class="col-span-1">
                    <x-onduleur-sidebar :connected="$connected ?? false" :lastUpdate="$lastUpdate ?? null" />
                </div>

                <!-- Contenu principal -->
                <div class="col-span-1 md:col-span-3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h1 class="text-3xl font-bold mb-8 text-blue-600">Configuration de l'Onduleur</h1>

                            @if (session('success'))
                                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                    <span class="block sm:inline">{{ session('success') }}</span>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                    <ul class="list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('onduleur.config.save') }}" class="space-y-6">
                                @csrf

                                <!-- Sélection du type d'onduleur -->
                                <div>
                                    <label for="inverter_type" class="block text-sm font-medium text-gray-700">Type d'Onduleur</label>
                                    <select id="inverter_type" name="inverter_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="sungrow" {{ old('inverter_type', config('inverters.default')) === 'sungrow' ? 'selected' : '' }}>SunGrow</option>
                                        <option value="huawei" {{ old('inverter_type', config('inverters.default')) === 'huawei' ? 'selected' : '' }}>Huawei</option>
                                        <option value="sma" {{ old('inverter_type', config('inverters.default')) === 'sma' ? 'selected' : '' }}>SMA</option>
                                        <option value="fronius" {{ old('inverter_type', config('inverters.default')) === 'fronius' ? 'selected' : '' }}>Fronius</option>
                                        <option value="schneider" {{ old('inverter_type', config('inverters.default')) === 'schneider' ? 'selected' : '' }}>Schneider Electric</option>
                                        <option value="abb" {{ old('inverter_type', config('inverters.default')) === 'abb' ? 'selected' : '' }}>ABB</option>
                                        <option value="delta" {{ old('inverter_type', config('inverters.default')) === 'delta' ? 'selected' : '' }}>Delta</option>
                                        <option value="goodwe" {{ old('inverter_type', config('inverters.default')) === 'goodwe' ? 'selected' : '' }}>GoodWe</option>
                                    </select>
                                </div>

                                <!-- Paramètres communs -->
                                <div class="common-settings">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="ip_address" class="block text-sm font-medium text-gray-700">Adresse IP</label>
                                            <input type="text" name="ip_address" id="ip_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="192.168.1.100">
                                        </div>

                                        <div>
                                            <label for="port" class="block text-sm font-medium text-gray-700">Port</label>
                                            <input type="number" name="port" id="port" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="502">
                                        </div>
                                    </div>
                                </div>

                                <!-- Paramètres spécifiques -->
                                <div class="specific-settings space-y-6">
                                    <!-- Huawei -->
                                    <div class="huawei-settings hidden">
                                        <div>
                                            <label for="api_key" class="block text-sm font-medium text-gray-700">Clé API</label>
                                            <input type="password" name="api_key" id="api_key" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>

                                    <!-- Schneider / Delta -->
                                    <div class="auth-settings hidden">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label for="username" class="block text-sm font-medium text-gray-700">Nom d'utilisateur</label>
                                                <input type="text" name="username" id="username" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            </div>
                                            <div>
                                                <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                                                <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ABB / GoodWe -->
                                    <div class="serial-settings hidden">
                                        <div>
                                            <label for="serial_number" class="block text-sm font-medium text-gray-700">Numéro de série</label>
                                            <input type="text" name="serial_number" id="serial_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>

                                <!-- Options avancées -->
                                <div class="border-t pt-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Options Avancées</h3>
                                    <div class="space-y-4">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input type="checkbox" name="auto_detection" id="auto_detection" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ config('inverters.auto_detection') ? 'checked' : '' }}>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="auto_detection" class="font-medium text-gray-700">Détection automatique</label>
                                                <p class="text-gray-500">Activer la détection automatique du type d'onduleur</p>
                                            </div>
                                        </div>

                                        <div>
                                            <label for="update_interval" class="block text-sm font-medium text-gray-700">Intervalle de mise à jour (secondes)</label>
                                            <input type="number" name="update_interval" id="update_interval" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ config('inverters.update_interval') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Boutons d'action -->
                                <div class="flex justify-end space-x-4">
                                    <button type="button" id="test-connection" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Tester la connexion
                                    </button>
                                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Enregistrer la configuration
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inverterType = document.getElementById('inverter_type');
            const commonSettings = document.querySelector('.common-settings');
            const huaweiSettings = document.querySelector('.huawei-settings');
            const authSettings = document.querySelector('.auth-settings');
            const serialSettings = document.querySelector('.serial-settings');

            function updateVisibleFields() {
                // Cacher tous les champs spécifiques
                huaweiSettings.classList.add('hidden');
                authSettings.classList.add('hidden');
                serialSettings.classList.add('hidden');

                // Afficher les champs appropriés selon le type d'onduleur
                switch(inverterType.value) {
                    case 'huawei':
                        huaweiSettings.classList.remove('hidden');
                        break;
                    case 'schneider':
                    case 'delta':
                        authSettings.classList.remove('hidden');
                        break;
                    case 'abb':
                    case 'goodwe':
                        serialSettings.classList.remove('hidden');
                        break;
                }
            }

            inverterType.addEventListener('change', updateVisibleFields);
            updateVisibleFields();

            // Gestion du test de connexion
            document.getElementById('test-connection').addEventListener('click', async function() {
                try {
                    const response = await fetch('/api/onduleur/test-connection', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            type: inverterType.value,
                            ip_address: document.getElementById('ip_address').value,
                            port: document.getElementById('port').value,
                            // Ajouter les autres champs selon le type
                            api_key: document.getElementById('api_key')?.value,
                            username: document.getElementById('username')?.value,
                            password: document.getElementById('password')?.value,
                            serial_number: document.getElementById('serial_number')?.value,
                        })
                    });

                    const result = await response.json();
                    
                    if (result.success) {
                        alert('Connexion réussie !');
                    } else {
                        alert('Échec de la connexion : ' + result.message);
                    }
                } catch (error) {
                    alert('Erreur lors du test de connexion : ' + error.message);
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
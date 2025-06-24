<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div id="success-message" class="mb-4 p-4 text-green-800 bg-green-100 border border-green-200 rounded">
                            {{ session('success') }}
                        </div>
                        <script>
                            setTimeout(() => {
                                const successMessage = document.getElementById('success-message');
                                if (successMessage) {
                                    successMessage.style.display = 'none';
                                }
                            }, 10000); // 10 secondes
                        </script>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 text-red-800 bg-red-100 border border-red-200 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <h1 class="text-2xl font-bold mb-4">{{ __('Demande de devis') }}</h1>
                    <form action="{{ route('devis.store') }}" method="POST">
                        @csrf
                        <!-- Informations personnelles -->
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold mb-4">{{ __('Informations personnelles') }}</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="nom" class="block text-sm font-medium text-gray-700">{{ __('Nom') }} *</label>
                                    <input type="text" name="nom" id="nom" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label for="prenom" class="block text-sm font-medium text-gray-700">{{ __('Prénom') }} *</label>
                                    <input type="text" name="prenom" id="prenom" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }} *</label>
                                    <input type="email" name="email" id="email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label for="telephone" class="block text-sm font-medium text-gray-700">{{ __('Téléphone') }} *</label>
                                    <input type="tel" name="telephone" id="telephone" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Informations sur le lieu d'installation -->
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold mb-4">{{ __('Lieu d\'installation') }}</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="adresse" class="block text-sm font-medium text-gray-700">{{ __('Adresse complète') }} *</label>
                                    <input type="text" name="adresse" id="adresse" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label for="type_batiment" class="block text-sm font-medium text-gray-700">{{ __('Type de bâtiment') }} *</label>
                                    <select name="type_batiment" id="type_batiment" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">{{ __('Sélectionnez') }}</option>
                                        <option value="maison">{{ __('Maison individuelle') }}</option>
                                        <option value="appartement">{{ __('Appartement') }}</option>
                                        <option value="local_commercial">{{ __('Local commercial') }}</option>
                                        <option value="batiment_industriel">{{ __('Bâtiment industriel') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Consommation énergétique -->
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold mb-4">{{ __('Consommation énergétique') }}</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="facture_mensuelle" class="block text-sm font-medium text-gray-700">{{ __('Facture électrique mensuelle moyenne (CFA)') }}</label>
                                    <input type="number" name="facture_mensuelle" id="facture_mensuelle" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label for="consommation_annuelle" class="block text-sm font-medium text-gray-700">{{ __('Consommation annuelle (kWh)') }}</label>
                                    <input type="number" name="consommation_annuelle" id="consommation_annuelle" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Caractéristiques du toit -->
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold mb-4">{{ __('Caractéristiques du toit') }}</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="type_toiture" class="block text-sm font-medium text-gray-700">{{ __('Type de toiture') }}</label>
                                    <select name="type_toiture" id="type_toiture" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">{{ __('Sélectionnez') }}</option>
                                        <option value="tuiles">{{ __('Tuiles') }}</option>
                                        <option value="ardoises">{{ __('Ardoises') }}</option>
                                        <option value="toit_plat">{{ __('Toit plat') }}</option>
                                        <option value="metal">{{ __('Métallique') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="orientation" class="block text-sm font-medium text-gray-700">{{ __('Orientation principale du toit') }}</label>
                                    <select name="orientation" id="orientation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">{{ __('Sélectionnez') }}</option>
                                        <option value="sud">{{ __('Sud') }}</option>
                                        <option value="sud-est">{{ __('Sud-Est') }}</option>
                                        <option value="sud-ouest">{{ __('Sud-Ouest') }}</option>
                                        <option value="est">{{ __('Est') }}</option>
                                        <option value="ouest">{{ __('Ouest') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Projet et objectifs -->
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold mb-4">{{ __('Votre projet') }}</h2>
                            <div>
                                <label for="objectifs" class="block text-sm font-medium text-gray-700">{{ __('Objectifs principaux') }}</label>
                                <div class="mt-2 space-y-2">
                                    <div>
                                        <input type="checkbox" name="objectifs[]" id="autoconsommation" value="autoconsommation" class="rounded border-gray-300">
                                        <label for="autoconsommation" class="ml-2">{{ __('Autoconsommation') }}</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" name="objectifs[]" id="revente" value="revente" class="rounded border-gray-300">
                                        <label for="revente" class="ml-2">{{ __('Revente totale') }}</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" name="objectifs[]" id="autonomie" value="autonomie" class="rounded border-gray-300">
                                        <label for="autonomie" class="ml-2">{{ __('Autonomie énergétique') }}</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <label for="message" class="block text-sm font-medium text-gray-700">{{ __('Informations complémentaires') }}</label>
                                <textarea name="message" id="message" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Précisez vos attentes, contraintes particulières ou questions..."></textarea>
                            </div>
                        </div>

                        <div class="mb-4 text-sm text-gray-600">
                            * {{ __('Champs obligatoires') }}
                        </div>

                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                            {{ __('Envoyer ma demande de devis') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
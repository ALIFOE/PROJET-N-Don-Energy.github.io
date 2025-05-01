<x-app-layout>
    <style>
        .required::after {
            content: ' *';
            color: red;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nouvelle demande de dimensionnement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Succès!</strong>
                            <span class="block sm:inline"> Votre demande de dimensionnement a été envoyée avec succès.</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Erreur!</strong>
                            <span class="block sm:inline"> Une erreur est survenue lors de l'envoi de votre demande. Veuillez réessayer.</span>
                        </div>
                    @endif

                    <form action="{{ route('dimensionnements.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Informations personnelles -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Informations personnelles') }}</h3>
                            <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                <div>
                                    <x-input-label for="nom" :value="__('Nom complet')" class="required" />
                                    <x-text-input id="nom" name="nom" type="text" class="mt-1 block w-full" :value="old('nom')" required autofocus />
                                    <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="email" :value="__('Email')" class="required" />
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', auth()->user()->email)" required />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="telephone" :value="__('Téléphone')" class="required" />
                                    <x-text-input id="telephone" name="telephone" type="tel" class="mt-1 block w-full" :value="old('telephone')" required />
                                    <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="adresse" :value="__('Adresse')" class="required" />
                                    <x-text-input id="adresse" name="adresse" type="text" class="mt-1 block w-full" :value="old('adresse')" required />
                                    <x-input-error :messages="$errors->get('adresse')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Caractéristiques du projet -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Caractéristiques du projet') }}</h3>
                            <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                <div>
                                    <x-input-label for="type_logement" :value="__('Type de logement')" class="required" />
                                    <select id="type_logement" name="type_logement" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="maison" @selected(old('type_logement') == 'maison')>{{ __('Maison') }}</option>
                                        <option value="appartement" @selected(old('type_logement') == 'appartement')>{{ __('Appartement') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('type_logement')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="surface_toiture" :value="__('Surface de toiture disponible (m²)')" class="required" />
                                    <x-text-input id="surface_toiture" name="surface_toiture" type="number" step="0.01" class="mt-1 block w-full" :value="old('surface_toiture')" required />
                                    <x-input-error :messages="$errors->get('surface_toiture')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="orientation" :value="__('Orientation principale')" class="required" />
                                    <select id="orientation" name="orientation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="sud" @selected(old('orientation') == 'sud')>{{ __('Sud') }}</option>
                                        <option value="sud-est" @selected(old('orientation') == 'sud-est')>{{ __('Sud-Est') }}</option>
                                        <option value="sud-ouest" @selected(old('orientation') == 'sud-ouest')>{{ __('Sud-Ouest') }}</option>
                                        <option value="autre" @selected(old('orientation') == 'autre')>{{ __('Autre') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('orientation')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="type_installation" :value="__('Type d\'installation')" class="required" />
                                    <select id="type_installation" name="type_installation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="toiture" @selected(old('type_installation') == 'toiture')>{{ __('Toiture') }}</option>
                                        <option value="sol" @selected(old('type_installation') == 'sol')>{{ __('Sol') }}</option>
                                        <option value="ombriere" @selected(old('type_installation') == 'ombriere')>{{ __('Ombrière') }}</option>
                                        <option value="autre" @selected(old('type_installation') == 'autre')>{{ __('Autre') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('type_installation')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="budget" :value="__('Budget envisagé (€)')" class="required" />
                                    <x-text-input id="budget" name="budget" type="number" step="100" class="mt-1 block w-full" :value="old('budget')" required />
                                    <x-input-error :messages="$errors->get('budget')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Consommation et équipements -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Consommation et équipements') }}</h3>
                            <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                <div>
                                    <x-input-label for="facture_annuelle" :value="__('Facture annuelle (€)')" class="required" />
                                    <x-text-input id="facture_annuelle" name="facture_annuelle" type="number" step="0.01" class="mt-1 block w-full" :value="old('facture_annuelle')" required />
                                    <x-input-error :messages="$errors->get('facture_annuelle')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="fournisseur" :value="__('Fournisseur d\'électricité')" class="required" />
                                    <x-text-input id="fournisseur" name="fournisseur" type="text" class="mt-1 block w-full" :value="old('fournisseur')" required />
                                    <x-input-error :messages="$errors->get('fournisseur')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="nb_personnes" :value="__('Nombre de personnes')" class="required" />
                                    <x-text-input id="nb_personnes" name="nb_personnes" type="number" min="1" class="mt-1 block w-full" :value="old('nb_personnes')" required />
                                    <x-input-error :messages="$errors->get('nb_personnes')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label :value="__('Équipements')" class="required" />
                                    <div class="mt-2 space-y-2">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="equipements[]" value="chauffage" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                @checked(is_array(old('equipements')) && in_array('chauffage', old('equipements')))>
                                            <span class="ml-2">{{ __('Chauffage électrique') }}</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="equipements[]" value="ballon" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                @checked(is_array(old('equipements')) && in_array('ballon', old('equipements')))>
                                            <span class="ml-2">{{ __('Ballon eau chaude électrique') }}</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="equipements[]" value="climatisation" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                @checked(is_array(old('equipements')) && in_array('climatisation', old('equipements')))>
                                            <span class="ml-2">{{ __('Climatisation') }}</span>
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('equipements')" class="mt-2" />
                                </div>

                                <div class="sm:col-span-2">
                                    <x-input-label :value="__('Objectifs du projet')" class="required" />
                                    <div class="mt-2 space-y-2">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="objectifs[]" value="reduction" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                @checked(is_array(old('objectifs')) && in_array('reduction', old('objectifs')))>
                                            <span class="ml-2">{{ __('Réduction de la facture') }}</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="objectifs[]" value="autoproduction" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                @checked(is_array(old('objectifs')) && in_array('autoproduction', old('objectifs')))>
                                            <span class="ml-2">{{ __('Autoproduction') }}</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="objectifs[]" value="revente" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                @checked(is_array(old('objectifs')) && in_array('revente', old('objectifs')))>
                                            <span class="ml-2">{{ __('Revente d\'électricité') }}</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="objectifs[]" value="environnement" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                @checked(is_array(old('objectifs')) && in_array('environnement', old('objectifs')))>
                                            <span class="ml-2">{{ __('Impact environnemental') }}</span>
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('objectifs')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-secondary-button type="button" onclick="window.history.back()">
                                {{ __('Annuler') }}
                            </x-secondary-button>

                            <x-primary-button class="ml-4" onclick="return validateForm()">
                                {{ __('Soumettre la demande') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validateForm() {
            const equipements = document.querySelectorAll('input[name="equipements[]"]:checked');
            const objectifs = document.querySelectorAll('input[name="objectifs[]"]:checked');

            if (equipements.length === 0) {
                alert('Veuillez sélectionner au moins un équipement.');
                return false;
            }

            if (objectifs.length === 0) {
                alert('Veuillez sélectionner au moins un objectif.');
                return false;
            }

            return true;
        }
    </script>
</x-app-layout>
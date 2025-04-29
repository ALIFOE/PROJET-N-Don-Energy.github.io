<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nouvelle demande de dimensionnement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('dimensionnements.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Informations personnelles') }}</h3>
                            <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                <div>
                                    <x-input-label for="nom" :value="__('Nom complet')" />
                                    <x-text-input id="nom" name="nom" type="text" class="mt-1 block w-full" :value="old('nom')" required autofocus />
                                    <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', auth()->user()->email)" required />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="telephone" :value="__('Téléphone')" />
                                    <x-text-input id="telephone" name="telephone" type="tel" class="mt-1 block w-full" :value="old('telephone')" required />
                                    <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="adresse" :value="__('Adresse')" />
                                    <x-text-input id="adresse" name="adresse" type="text" class="mt-1 block w-full" :value="old('adresse')" required />
                                    <x-input-error :messages="$errors->get('adresse')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="ville" :value="__('Ville')" />
                                    <x-text-input id="ville" name="ville" type="text" class="mt-1 block w-full" :value="old('ville')" required />
                                    <x-input-error :messages="$errors->get('ville')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="code_postal" :value="__('Code postal')" />
                                    <x-text-input id="code_postal" name="code_postal" type="text" class="mt-1 block w-full" :value="old('code_postal')" required />
                                    <x-input-error :messages="$errors->get('code_postal')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="pays" :value="__('Pays')" />
                                    <x-text-input id="pays" name="pays" type="text" class="mt-1 block w-full" :value="old('pays', 'France')" required />
                                    <x-input-error :messages="$errors->get('pays')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Informations sur le logement') }}</h3>
                            <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                <div>
                                    <x-input-label for="type_logement" :value="__('Type de logement')" />
                                    <select id="type_logement" name="type_logement" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="maison" @selected(old('type_logement') == 'maison')>{{ __('Maison') }}</option>
                                        <option value="appartement" @selected(old('type_logement') == 'appartement')>{{ __('Appartement') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('type_logement')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="surface_toiture" :value="__('Surface disponible en toiture (m²)')" />
                                    <x-text-input id="surface_toiture" name="surface_toiture" type="number" step="0.01" class="mt-1 block w-full" :value="old('surface_toiture')" required />
                                    <x-input-error :messages="$errors->get('surface_toiture')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="orientation" :value="__('Orientation principale de la toiture')" />
                                    <select id="orientation" name="orientation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="sud" @selected(old('orientation') == 'sud')>{{ __('Sud') }}</option>
                                        <option value="sud-est" @selected(old('orientation') == 'sud-est')>{{ __('Sud-Est') }}</option>
                                        <option value="sud-ouest" @selected(old('orientation') == 'sud-ouest')>{{ __('Sud-Ouest') }}</option>
                                        <option value="autre" @selected(old('orientation') == 'autre')>{{ __('Autre') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('orientation')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="type_installation" :value="__('Type d\'installation souhaité')" />
                                    <select id="type_installation" name="type_installation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="toiture" @selected(old('type_installation') == 'toiture')>{{ __('Installation en toiture') }}</option>
                                        <option value="sol" @selected(old('type_installation') == 'sol')>{{ __('Installation au sol') }}</option>
                                        <option value="ombriere" @selected(old('type_installation') == 'ombriere')>{{ __('Ombrière') }}</option>
                                        <option value="autre" @selected(old('type_installation') == 'autre')>{{ __('Autre') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('type_installation')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Équipements et consommation') }}</h3>
                            <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                <div>
                                    <x-input-label :value="__('Équipements énergivores')" />
                                    <div class="mt-2 space-y-2">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="equipements[]" value="climatisation" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                @checked(is_array(old('equipements')) && in_array('climatisation', old('equipements')))>
                                            <span class="ml-2">{{ __('Climatisation') }}</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="equipements[]" value="pompe_chaleur" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                @checked(is_array(old('equipements')) && in_array('pompe_chaleur', old('equipements')))>
                                            <span class="ml-2">{{ __('Pompe à chaleur') }}</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="equipements[]" value="vehicule_electrique" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                @checked(is_array(old('equipements')) && in_array('vehicule_electrique', old('equipements')))>
                                            <span class="ml-2">{{ __('Véhicule électrique') }}</span>
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('equipements')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label :value="__('Objectifs du projet')" />
                                    <div class="mt-2 space-y-2">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="objectifs[]" value="autonomie" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                @checked(is_array(old('objectifs')) && in_array('autonomie', old('objectifs')))>
                                            <span class="ml-2">{{ __('Autonomie énergétique') }}</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="objectifs[]" value="economie" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                @checked(is_array(old('objectifs')) && in_array('economie', old('objectifs')))>
                                            <span class="ml-2">{{ __('Économies sur facture') }}</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="objectifs[]" value="ecologie" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                @checked(is_array(old('objectifs')) && in_array('ecologie', old('objectifs')))>
                                            <span class="ml-2">{{ __('Démarche écologique') }}</span>
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('objectifs')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="facture_annuelle" :value="__('Facture d\'électricité annuelle (€)')" />
                                    <x-text-input id="facture_annuelle" name="facture_annuelle" type="number" step="0.01" class="mt-1 block w-full" :value="old('facture_annuelle')" required />
                                    <x-input-error :messages="$errors->get('facture_annuelle')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="fournisseur" :value="__('Fournisseur d\'électricité actuel')" />
                                    <x-text-input id="fournisseur" name="fournisseur" type="text" class="mt-1 block w-full" :value="old('fournisseur')" required />
                                    <x-input-error :messages="$errors->get('fournisseur')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="nb_personnes" :value="__('Nombre de personnes dans le foyer')" />
                                    <x-text-input id="nb_personnes" name="nb_personnes" type="number" min="1" class="mt-1 block w-full" :value="old('nb_personnes')" required />
                                    <x-input-error :messages="$errors->get('nb_personnes')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="budget" :value="__('Budget envisagé (€)')" />
                                    <x-text-input id="budget" name="budget" type="number" step="100" class="mt-1 block w-full" :value="old('budget')" required />
                                    <x-input-error :messages="$errors->get('budget')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-secondary-button type="button" onclick="window.history.back()">
                                {{ __('Annuler') }}
                            </x-secondary-button>

                            <x-primary-button class="ml-4">
                                {{ __('Soumettre la demande') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
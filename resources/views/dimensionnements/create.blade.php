<x-app-layout>
    <style>
        .required::after {
            content: ' *';
            color: #EF4444;
        }
        .form-section {
            @apply border-b border-gray-200 pb-8 mb-8 transition-all duration-300 ease-in-out;
        }
        .form-section:hover {
            @apply bg-gray-50;
        }
        .section-title {
            @apply flex items-center space-x-2 text-xl font-semibold text-gray-900 mb-6;
        }
        .form-grid {
            @apply grid grid-cols-1 md:grid-cols-2 gap-6;
        }
        .form-group {
            @apply space-y-2;
        }
        .step-indicator {
            @apply flex justify-between items-center mb-8;
        }
        .step {
            @apply flex items-center;
        }
        .step-number {
            @apply w-8 h-8 rounded-full bg-blue-500 text-white flex items-center justify-center font-semibold mr-2;
        }
        .step-title {
            @apply text-sm font-medium;
        }
        .step-line {
            @apply flex-1 h-0.5 bg-gray-200 mx-4;
        }
    </style>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Nouvelle demande de dimensionnement') }}
            </h2>
            <a href="{{ route('dimensionnements.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700">
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Section titre et description -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ __('Dimensionnement Solaire Personnalisé') }}</h1>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    {{ __('Optimisez votre installation solaire en nous fournissant les détails de votre projet. Notre équipe d\'experts analysera vos besoins pour vous proposer la solution la plus adaptée.') }}
                </p>
                <div class="mt-4 flex justify-center space-x-4">
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('Étude gratuite') }}
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('Réponse sous 48h') }}
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        {{ __('Installation professionnelle') }}
                    </div>
                </div>
            </div>            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <!-- Messages de notification -->
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm leading-5 font-medium">{{ __('Succès!') }}</p>
                                    <p class="text-sm leading-5">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if(session('dimensionnement_success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm leading-5 font-medium">{{ __('Demande envoyée!') }}</p>
                                    <p class="text-sm leading-5">{{ session('dimensionnement_success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm leading-5">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- <!-- Indicateur d'étapes -->
                    <div class="step-indicator">
                        <div class="step">
                            <div class="step-number">1</div>
                            <span class="step-title">{{ __('Informations personnelles') }}</span>
                        </div>
                        <div class="step-line"></div>
                        <div class="step">
                            <div class="step-number">2</div>
                            <span class="step-title">{{ __('Informations sur le logement') }}</span>
                        </div>
                        <div class="step-line"></div>
                        <div class="step">
                            <div class="step-number">3</div>
                            <span class="step-title">{{ __('Équipements et consommation') }}</span>
                        </div>
                    </div> --}}

                    <form action="{{ route('dimensionnements.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <div class="form-section">
                            <div class="section-title">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>{{ __('Informations personnelles') }}</span>
                            </div>
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

                                <div>
                                    <x-input-label for="ville" :value="__('Ville')" class="required" />
                                    <x-text-input id="ville" name="ville" type="text" class="mt-1 block w-full" :value="old('ville')" required />
                                    <x-input-error :messages="$errors->get('ville')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="code_postal" :value="__('Code postal')" class="required" />
                                    <x-text-input id="code_postal" name="code_postal" type="text" class="mt-1 block w-full" :value="old('code_postal')" required />
                                    <x-input-error :messages="$errors->get('code_postal')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="pays" :value="__('Pays')" class="required" />
                                    <x-text-input id="pays" name="pays" type="text" class="mt-1 block w-full" :value="old('pays', 'France')" required />
                                    <x-input-error :messages="$errors->get('pays')" class="mt-2" />
                                </div>
                            </div>
                        </div>                        <div class="form-section">
                            <div class="section-title">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                <span>{{ __('Informations sur le logement') }}</span>
                            </div>
                            <div class="mt-6 form-grid">
                                <div>
                                    <x-input-label for="type_logement" :value="__('Type de logement')" class="required" />
                                    <select id="type_logement" name="type_logement" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="maison" @selected(old('type_logement') == 'maison')>{{ __('Maison') }}</option>
                                        <option value="appartement" @selected(old('type_logement') == 'appartement')>{{ __('Appartement') }}</option>
                                        <option value="commerce" @selected(old('type_logement') == 'commerce')>{{ __('Commerce') }}</option>
                                        <option value="industriel" @selected(old('type_logement') == 'industriel')>{{ __('Bâtiment industriel') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('type_logement')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="surface_toiture" :value="__('Surface disponible en toiture (m²)')" class="required" />
                                    <x-text-input id="surface_toiture" name="surface_toiture" type="number" step="0.01" class="mt-1 block w-full" :value="old('surface_toiture')" required />
                                    <x-input-error :messages="$errors->get('surface_toiture')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="orientation" :value="__('Orientation principale de la toiture')" class="required" />
                                    <select id="orientation" name="orientation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="sud" @selected(old('orientation') == 'sud')>{{ __('Sud') }}</option>
                                        <option value="sud-est" @selected(old('orientation') == 'sud-est')>{{ __('Sud-Est') }}</option>
                                        <option value="sud-ouest" @selected(old('orientation') == 'sud-ouest')>{{ __('Sud-Ouest') }}</option>
                                        <option value="autre" @selected(old('orientation') == 'autre')>{{ __('Autre') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('orientation')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="type_installation" :value="__('Type d\'installation souhaité')" class="required" />
                                    <select id="type_installation" name="type_installation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="toiture" @selected(old('type_installation') == 'toiture')>{{ __('Installation en toiture') }}</option>
                                        <option value="sol" @selected(old('type_installation') == 'sol')>{{ __('Installation au sol') }}</option>
                                        <option value="ombriere" @selected(old('type_installation') == 'ombriere')>{{ __('Ombrière') }}</option>
                                        <option value="autre" @selected(old('type_installation') == 'autre')>{{ __('Autre') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('type_installation')" class="mt-2" />
                                </div>
                            </div>
                        </div>                        <div class="form-section">
                            <div class="section-title">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span>{{ __('Équipements et consommation') }}</span>
                            </div>
                            <div class="mt-6 form-grid">
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
                                    <x-input-label for="facture_annuelle" :value="__('Facture d\'électricité annuelle (CFA)')" class="required" />
                                    <x-text-input id="facture_annuelle" name="facture_annuelle" type="number" step="0.01" class="mt-1 block w-full" :value="old('facture_annuelle')" required />
                                    <x-input-error :messages="$errors->get('facture_annuelle')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="fournisseur" :value="__('Fournisseur d\'électricité actuel')" class="required" />
                                    <x-text-input id="fournisseur" name="fournisseur" type="text" class="mt-1 block w-full" :value="old('fournisseur')" required />
                                    <x-input-error :messages="$errors->get('fournisseur')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="nb_personnes" :value="__('Nombre de personnes dans le foyer')" class="required" />
                                    <x-text-input id="nb_personnes" name="nb_personnes" type="number" min="1" class="mt-1 block w-full" :value="old('nb_personnes')" required />
                                    <x-input-error :messages="$errors->get('nb_personnes')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="budget" :value="__('Budget envisagé (CFA)')" class="required" />
                                    <x-text-input id="budget" name="budget" type="number" step="100" class="mt-1 block w-full" :value="old('budget')" required />
                                    <x-input-error :messages="$errors->get('budget')" class="mt-2" />
                                </div>
                            </div>
                        </div>                        <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                            <button type="button" 
                                onclick="window.history.back()" 
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                {{ __('Retour') }}
                            </button>

                            <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:border-blue-700 active:bg-blue-800 transition duration-150 ease-in-out">
                                {{ __('Soumettre la demande') }}
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
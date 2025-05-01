<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier la demande de dimensionnement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('dimensionnements.update', $dimensionnement) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Informations personnelles -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Informations personnelles') }}</h3>
                            <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                <div>
                                    <x-input-label for="nom" :value="__('Nom complet')" />
                                    <x-text-input id="nom" name="nom" type="text" class="mt-1 block w-full" :value="old('nom', $dimensionnement->nom)" required autofocus />
                                    <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $dimensionnement->email)" required />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="telephone" :value="__('Téléphone')" />
                                    <x-text-input id="telephone" name="telephone" type="tel" class="mt-1 block w-full" :value="old('telephone', $dimensionnement->telephone)" required />
                                    <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="adresse" :value="__('Adresse')" />
                                    <x-text-input id="adresse" name="adresse" type="text" class="mt-1 block w-full" :value="old('adresse', $dimensionnement->adresse)" required />
                                    <x-input-error :messages="$errors->get('adresse')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Caractéristiques du projet -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Caractéristiques du projet') }}</h3>
                            <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                <div>
                                    <x-input-label for="type_logement" :value="__('Type de logement')" />
                                    <select id="type_logement" name="type_logement" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="maison" @selected(old('type_logement', $dimensionnement->type_logement) == 'maison')>{{ __('Maison') }}</option>
                                        <option value="appartement" @selected(old('type_logement', $dimensionnement->type_logement) == 'appartement')>{{ __('Appartement') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('type_logement')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="surface_toiture" :value="__('Surface de toiture (m²)')" />
                                    <x-text-input id="surface_toiture" name="surface_toiture" type="number" step="0.01" class="mt-1 block w-full" :value="old('surface_toiture', $dimensionnement->surface_toiture)" required />
                                    <x-input-error :messages="$errors->get('surface_toiture')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="orientation" :value="__('Orientation')" />
                                    <select id="orientation" name="orientation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="sud" @selected(old('orientation', $dimensionnement->orientation) == 'sud')>{{ __('Sud') }}</option>
                                        <option value="sud-est" @selected(old('orientation', $dimensionnement->orientation) == 'sud-est')>{{ __('Sud-Est') }}</option>
                                        <option value="sud-ouest" @selected(old('orientation', $dimensionnement->orientation) == 'sud-ouest')>{{ __('Sud-Ouest') }}</option>
                                        <option value="autre" @selected(old('orientation', $dimensionnement->orientation) == 'autre')>{{ __('Autre') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('orientation')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="type_installation" :value="__('Type d\'installation')" />
                                    <select id="type_installation" name="type_installation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="toiture" @selected(old('type_installation', $dimensionnement->type_installation) == 'toiture')>{{ __('Toiture') }}</option>
                                        <option value="sol" @selected(old('type_installation', $dimensionnement->type_installation) == 'sol')>{{ __('Sol') }}</option>
                                        <option value="ombriere" @selected(old('type_installation', $dimensionnement->type_installation) == 'ombriere')>{{ __('Ombrière') }}</option>
                                        <option value="autre" @selected(old('type_installation', $dimensionnement->type_installation) == 'autre')>{{ __('Autre') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('type_installation')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="budget" :value="__('Budget envisagé (€)')" />
                                    <x-text-input id="budget" name="budget" type="number" step="100" class="mt-1 block w-full" :value="old('budget', $dimensionnement->budget)" required />
                                    <x-input-error :messages="$errors->get('budget')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Consommation et équipements -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Consommation et équipements') }}</h3>
                            <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                <div>
                                    <x-input-label for="facture_annuelle" :value="__('Facture annuelle (€)')" />
                                    <x-text-input id="facture_annuelle" name="facture_annuelle" type="number" step="0.01" class="mt-1 block w-full" :value="old('facture_annuelle', $dimensionnement->facture_annuelle)" required />
                                    <x-input-error :messages="$errors->get('facture_annuelle')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="fournisseur" :value="__('Fournisseur d\'électricité')" />
                                    <x-text-input id="fournisseur" name="fournisseur" type="text" class="mt-1 block w-full" :value="old('fournisseur', $dimensionnement->fournisseur)" required />
                                    <x-input-error :messages="$errors->get('fournisseur')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="nb_personnes" :value="__('Nombre de personnes')" />
                                    <x-text-input id="nb_personnes" name="nb_personnes" type="number" min="1" class="mt-1 block w-full" :value="old('nb_personnes', $dimensionnement->nb_personnes)" required />
                                    <x-input-error :messages="$errors->get('nb_personnes')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label :value="__('Équipements')" />
                                    <div class="mt-2 space-y-2">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="equipements[]" value="chauffage" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                @checked(is_array(old('equipements', $dimensionnement->equipements)) && in_array('chauffage', old('equipements', $dimensionnement->equipements)))>
                                            <span class="ml-2">{{ __('Chauffage électrique') }}</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="equipements[]" value="ballon" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                @checked(is_array(old('equipements', $dimensionnement->equipements)) && in_array('ballon', old('equipements', $dimensionnement->equipements)))>
                                            <span class="ml-2">{{ __('Ballon eau chaude électrique') }}</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="equipements[]" value="climatisation" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                @checked(is_array(old('equipements', $dimensionnement->equipements)) && in_array('climatisation', old('equipements', $dimensionnement->equipements)))>
                                            <span class="ml-2">{{ __('Climatisation') }}</span>
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('equipements')" class="mt-2" />
                                </div>

                                <div class="sm:col-span-2">
                                    <x-input-label :value="__('Objectifs du projet')" />
                                    <div class="mt-2 space-y-2">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="objectifs[]" value="reduction" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                @checked(is_array(old('objectifs', $dimensionnement->objectifs)) && in_array('reduction', old('objectifs', $dimensionnement->objectifs)))>
                                            <span class="ml-2">{{ __('Réduction de la facture') }}</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="objectifs[]" value="autoproduction" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                @checked(is_array(old('objectifs', $dimensionnement->objectifs)) && in_array('autoproduction', old('objectifs', $dimensionnement->objectifs)))>
                                            <span class="ml-2">{{ __('Autoproduction') }}</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="objectifs[]" value="revente" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                @checked(is_array(old('objectifs', $dimensionnement->objectifs)) && in_array('revente', old('objectifs', $dimensionnement->objectifs)))>
                                            <span class="ml-2">{{ __('Revente d\'électricité') }}</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="objectifs[]" value="environnement" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                @checked(is_array(old('objectifs', $dimensionnement->objectifs)) && in_array('environnement', old('objectifs', $dimensionnement->objectifs)))>
                                            <span class="ml-2">{{ __('Impact environnemental') }}</span>
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('objectifs')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-secondary-button type="button" onclick="window.location.href='{{ route('dimensionnements.index') }}'">
                                {{ __('Annuler') }}
                            </x-secondary-button>

                            <x-primary-button class="ml-4">
                                {{ __('Mettre à jour') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
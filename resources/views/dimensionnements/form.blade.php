<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-8 text-center">Demande de dimensionnement</h1>

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <strong class="font-bold">Attention !</strong>
                            <ul class="mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('dimensionnements.store') }}" method="POST" class="max-w-3xl mx-auto">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Informations personnelles -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="nom">Nom complet</label>
                                <input type="text" name="nom" id="nom" value="{{ old('nom') }}" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('nom') border-red-500 @enderror" 
                                    required>
                                @error('nom')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="email">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('email') border-red-500 @enderror"
                                    required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="telephone">Téléphone</label>
                                <input type="tel" name="telephone" id="telephone" value="{{ old('telephone') }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('telephone') border-red-500 @enderror"
                                    required>
                                @error('telephone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="adresse">Adresse</label>
                                <input type="text" name="adresse" id="adresse" value="{{ old('adresse') }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('adresse') border-red-500 @enderror"
                                    required>
                                @error('adresse')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="ville">Ville</label>
                                <input type="text" name="ville" id="ville" value="{{ old('ville') }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('ville') border-red-500 @enderror"
                                    required>
                                @error('ville')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="code_postal">Code Postal</label>
                                <input type="text" name="code_postal" id="code_postal" value="{{ old('code_postal') }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('code_postal') border-red-500 @enderror"
                                    required>
                                @error('code_postal')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Informations techniques -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="type_logement">Type de logement</label>
                                <select name="type_logement" id="type_logement"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('type_logement') border-red-500 @enderror">
                                    <option value="maison" {{ old('type_logement') == 'maison' ? 'selected' : '' }}>Maison individuelle</option>
                                    <option value="appartement" {{ old('type_logement') == 'appartement' ? 'selected' : '' }}>Appartement</option>
                                    <option value="commerce" {{ old('type_logement') == 'commerce' ? 'selected' : '' }}>Local commercial</option>
                                    <option value="industriel" {{ old('type_logement') == 'industriel' ? 'selected' : '' }}>Bâtiment industriel</option>
                                </select>
                                @error('type_logement')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="surface_toiture">Surface de toiture disponible (m²)</label>
                                <input type="number" name="surface_toiture" id="surface_toiture" value="{{ old('surface_toiture') }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('surface_toiture') border-red-500 @enderror"
                                    min="0" step="0.01">
                                @error('surface_toiture')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="orientation">Orientation principale</label>
                                <select name="orientation" id="orientation"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('orientation') border-red-500 @enderror">
                                    <option value="sud" {{ old('orientation') == 'sud' ? 'selected' : '' }}>Sud</option>
                                    <option value="sud-est" {{ old('orientation') == 'sud-est' ? 'selected' : '' }}>Sud-Est</option>
                                    <option value="sud-ouest" {{ old('orientation') == 'sud-ouest' ? 'selected' : '' }}>Sud-Ouest</option>
                                    <option value="est" {{ old('orientation') == 'est' ? 'selected' : '' }}>Est</option>
                                    <option value="ouest" {{ old('orientation') == 'ouest' ? 'selected' : '' }}>Ouest</option>
                                </select>
                                @error('orientation')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="facture_annuelle">Facture électrique annuelle (FCFA)</label>
                                <input type="number" name="facture_annuelle" id="facture_annuelle" value="{{ old('facture_annuelle') }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('facture_annuelle') border-red-500 @enderror"
                                    min="0">
                                @error('facture_annuelle')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="fournisseur">Fournisseur d'électricité actuel</label>
                                <input type="text" name="fournisseur" id="fournisseur" value="{{ old('fournisseur') }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('fournisseur') border-red-500 @enderror">
                                @error('fournisseur')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="nb_personnes">Nombre de personnes dans le foyer</label>
                                <input type="number" name="nb_personnes" id="nb_personnes" value="{{ old('nb_personnes') }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('nb_personnes') border-red-500 @enderror"
                                    min="1">
                                @error('nb_personnes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 text-center">
                            <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Envoyer ma demande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
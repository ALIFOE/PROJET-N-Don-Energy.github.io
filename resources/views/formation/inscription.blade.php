<x-app-layout>
    <head>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f8f9fa;
            }
            .form-card {
                background: white;
                border-radius: 12px;
                padding: 2rem;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }
            .form-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            }
            .btn-submit {
                background-color: #1e88e5;
                color: white;
                padding: 0.5rem 1.5rem;
                border-radius: 5px;
                transition: all 0.3s ease;
            }
            .btn-submit:hover {
                background-color: #0d47a1;
            }
        </style>
    </head>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Succès !</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 5.652a1 1 0 10-1.414-1.414L10 7.586 7.066 4.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 12.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934z"/></svg>
                    </span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Erreur !</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 5.652a1 1 0 10-1.414-1.414L10 7.586 7.066 4.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 12.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934z"/></svg>
                    </span>
                </div>
            @endif

            <div class="form-card">
                <h1 class="text-3xl font-bold mb-8 text-center">Inscription à une Formation</h1>

                <form action="{{ route('formation.inscription') }}" method="POST">
                    @csrf

                    <!-- Informations personnelles -->
                    <div class="mb-4">
                        <label for="nom" class="block text-gray-700 font-medium">Nom complet</label>
                        <input type="text" id="nom" name="nom" class="w-full border-gray-300 rounded-lg shadow-sm" required minlength="2" value="{{ old('nom') }}">
                        @error('nom')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-medium">Adresse e-mail</label>
                        <input type="email" id="email" name="email" class="w-full border-gray-300 rounded-lg shadow-sm" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" value="{{ old('email') }}">
                        <p class="mt-1 text-sm text-gray-500">Un e-mail de confirmation vous sera envoyé après l'inscription</p>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="telephone" class="block text-gray-700 font-medium">Numéro de téléphone</label>
                        <input type="tel" id="telephone" name="telephone" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                        @error('telephone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="formation" class="block text-gray-700 font-medium">Choisissez une formation</label>
                        <select name="formation" id="formation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">Sélectionnez une formation</option>
                            <option value="Installation de Panneaux Solaires" {{ old('formation') == 'Installation de Panneaux Solaires' ? 'selected' : '' }}>Installation de Panneaux Solaires - 3 mois</option>
                            <option value="Maintenance et Dépannage" {{ old('formation') == 'Maintenance et Dépannage' ? 'selected' : '' }}>Maintenance et Dépannage -1 mois</option>
                            <option value="Conception de Projets Solaires" {{ old('formation') == 'Conception de Projets Solaires' ? 'selected' : '' }}>Conception de Projets Solaires - 3 Semaines </option>
                        </select>
                        @error('formation')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Informations supplémentaires -->
                    <div class="mb-4">
                        <label for="message" class="block text-gray-700 font-medium">Message ou questions (optionnel)</label>
                        <textarea id="message" name="message" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm"></textarea>
                    </div>

                    <!-- Bouton de soumission -->
                    <div class="text-center">
                        <button type="submit" class="btn-submit">S'inscrire</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

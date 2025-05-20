<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contact') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-6 py-12">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Contactez-nous</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Nos coordonnées</h2>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-blue-500 mt-1 mr-3"></i>
                            <span class="text-gray-600">Lomé-TOGO,<br>
Siège Social situé à Lomé-TOGO<br> quartier Totsi Gblenkomé <br>près de la salle de réunion des témoins de Jéhovah
<br>Annexe au bord des pavés de Totsi non loin de l'agence TogoCom</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone text-blue-500 mr-3"></i>
                            <span class="text-gray-600">(+228)91 20 43 73<br>(+228)92 53 14 55</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-blue-500 mr-3"></i>
                            <span class="text-gray-600">ecole.crefer@gmail.com</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Horaires d'ouverture</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Lundi - Vendredi</span>
                            <span class="text-gray-800 font-medium">9h00 - 18h00</span>
                        </div>
                        {{-- <div class="flex justify-between">
                            <span class="text-gray-600">Samedi</span>
                            <span class="text-gray-800 font-medium">10h00 - 16h00</span>
                        </div> --}}
                        <div class="flex justify-between">
                            <span class="text-gray-600">Samedi - Dimanche</span>
                            <span class="text-gray-800 font-medium">Fermé</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 mb-6 text-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Nous contacter</h2>
                <p class="text-gray-600">Remplissez le formulaire ci-dessous pour nous envoyer un message.</p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-8">
                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label for="nom" class="block text-gray-700 text-sm font-bold mb-2">Nom complet</label>
                        <input type="text" name="nom" id="nom" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="mb-6">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                        <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="mb-6">
                        <label for="telephone" class="block text-gray-700 text-sm font-bold mb-2">Téléphone</label>
                        <input type="tel" name="telephone" id="telephone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="mb-6">
                        <label for="sujet" class="block text-gray-700 text-sm font-bold mb-2">Sujet</label>
                        <input type="text" name="sujet" id="sujet" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="mb-6">
                        <label for="message" class="block text-gray-700 text-sm font-bold mb-2">Message</label>
                        <textarea name="message" id="message" rows="6" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" class="btn-primary transition duration-300">
                            Envoyer le message
                        </button>
                    </div>
                </form>
            </div>

            
        </div>
    </div>
</x-app-layout> 
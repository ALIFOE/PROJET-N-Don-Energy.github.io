<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $service->nom }}</h1>
                        <div class="prose max-w-none">
                            <p class="text-gray-600">{{ $service->description }}</p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Demander ce service</h2>
                        
                        @auth
                            <form action="{{ route('services.request.submit', $service) }}" method="POST" class="max-w-2xl">
                                @csrf
                                
                                <div class="grid grid-cols-1 gap-6">                                    <div>
                                        <label for="nom" class="block text-sm font-medium text-gray-700">Nom complet</label>
                                        <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                        @error('nom')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                        @error('email')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                                        <input type="tel" name="telephone" id="telephone" value="{{ old('telephone') }}" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50">                                        @error('telephone')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="description" class="block text-sm font-medium text-gray-700">Description de votre besoin</label>
                                        <textarea name="description" id="description" rows="4" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                                        @error('description')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>                                    <div class="flex items-center justify-end mt-4">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-600 active:bg-orange-700 focus:outline-none focus:border-orange-700 focus:ring ring-orange-300 disabled:opacity-25 transition ease-in-out duration-150">
                                            Envoyer la demande
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <div class="bg-gray-100 rounded-lg p-6">
                                <p class="text-gray-700">Veuillez vous <a href="{{ route('login') }}" class="text-orange-500 hover:text-orange-600">connecter</a> pour faire une demande de service.</p>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Nos Services</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($services as $service)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-300">
                        <div class="p-6">                            <div class="mb-4">
                                <h2 class="text-xl font-semibold text-gray-800">{{ $service->nom }}</h2>
                            </div>
                            
                            <p class="text-gray-600 mb-4">{{ Str::limit($service->description, 150) }}</p>
                            
                            <div class="mt-4">
                                <a href="{{ route('services.show', $service) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-600 active:bg-orange-700 focus:outline-none focus:border-orange-700 focus:ring ring-orange-300 disabled:opacity-25 transition">
                                    En savoir plus
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>

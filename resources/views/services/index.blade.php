<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nos Services') }}
        </h2>
    </x-slot>

    <!-- Styles locaux pour les services -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .solar-gradient {
            background: linear-gradient(135deg, #1e88e5 0%, #0d47a1 100%);
        }
        .card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        .btn-primary {
            background-color: #1e88e5;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 5px;
            transition: all 0.3s ease;
            display: inline-block;
        }
        .btn-primary:hover {
            background-color: #0d47a1;
        }
        .section-title {
            color: #1e88e5;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .service-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        .service-features li {
            margin-bottom: 0.5rem;
            color: #4b5563;
        }
        .service-features i {
            color: #10b981;
            margin-right: 0.5rem;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @forelse ($services as $service)
                            <div class="service-card">
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-cogs text-blue-500 text-2xl mr-3"></i>
                                    <h2 class="text-xl font-semibold">{{ $service->nom }}</h2>
                                </div>
                                <p class="text-gray-600 mb-4">{{ $service->description }}</p>
                                @if($service->image)
                                    <div class="mb-4">
                                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->nom }}" class="w-full h-48 object-cover rounded-lg">
                                    </div>
                                @endif
                                <div class="mt-6 text-center">
                                    <a href="{{ route('services.request.form', ['service' => $service->id]) }}" 
                                       class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                                        Demander un devis
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-8">
                                <p class="text-gray-500 text-lg">Aucun service n'est disponible pour le moment.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
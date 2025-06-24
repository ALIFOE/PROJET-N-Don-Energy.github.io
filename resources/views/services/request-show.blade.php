<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($service->image)
                        <div class="mb-8">
                            <img src="{{ Storage::url($service->image) }}" alt="{{ $service->nom }}" 
                                 class="w-full max-h-96 object-cover rounded-lg shadow-lg">
                        </div>
                    @endif

                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $service->nom }}</h1>
                        <div class="prose max-w-none">
                            <p class="text-gray-600">{{ $service->description }}</p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Informations de la demande de service</h2>
                        <div class="max-w-2xl bg-gray-50 rounded-lg p-6">
                            <ul class="space-y-4">
                                <li><span class="font-medium text-gray-700">Nom complet :</span> {{ $serviceRequest->nom ?? '-' }}</li>
                                <li><span class="font-medium text-gray-700">Email :</span> {{ $serviceRequest->email ?? '-' }}</li>
                                <li><span class="font-medium text-gray-700">Téléphone :</span> {{ $serviceRequest->telephone ?? '-' }}</li>
                                <li><span class="font-medium text-gray-700">Service demandé :</span> {{ $service->nom }}</li>
                                <li><span class="font-medium text-gray-700">Description :</span> {{ $serviceRequest->description ?? '-' }}</li>
                                <li><span class="font-medium text-gray-700">Date de la demande :</span> {{ $serviceRequest->created_at ? $serviceRequest->created_at->format('d/m/Y') : '-' }}</li>
                            </ul>
                            @if(isset($serviceRequest->champs_specifiques) && count($serviceRequest->champs_specifiques) > 0)
                                <div class="mt-6">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Informations complémentaires :</h3>
                                    <ul class="list-disc pl-6">
                                        @foreach($serviceRequest->champs_specifiques as $champ => $valeur)
                                            <li><span class="font-medium">{{ $champ }}</span> : {{ $valeur }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

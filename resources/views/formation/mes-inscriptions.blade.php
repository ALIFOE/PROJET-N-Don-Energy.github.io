<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mes inscriptions aux formations
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($inscriptions->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-600">Vous n'avez pas encore d'inscription à une formation.</p>
                            <a href="{{ route('formation') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                                Voir les formations disponibles
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Formation
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date d'inscription
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Statut
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Documents
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($inscriptions as $inscription)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $inscription->formation->nom }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    Durée : {{ $inscription->formation->duree }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $inscription->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $inscription->statut === 'acceptee' ? 'bg-green-100 text-green-800' : 
                                                       ($inscription->statut === 'refusee' ? 'bg-red-100 text-red-800' : 
                                                       'bg-yellow-100 text-yellow-800') }}">
                                                    {{ ucfirst($inscription->statut) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div class="space-y-1">
                                                    <a href="{{ Storage::url($inscription->acte_naissance_path) }}" 
                                                       target="_blank"
                                                       class="text-blue-600 hover:text-blue-800 block">
                                                        Acte de naissance
                                                    </a>
                                                    <a href="{{ Storage::url($inscription->cni_path) }}" 
                                                       target="_blank"
                                                       class="text-blue-600 hover:text-blue-800 block">
                                                        CNI
                                                    </a>
                                                    <a href="{{ Storage::url($inscription->diplome_path) }}" 
                                                       target="_blank"
                                                       class="text-blue-600 hover:text-blue-800 block">
                                                        Diplôme
                                                    </a>
                                                    @if($inscription->autres_documents_paths)
                                                        @foreach($inscription->autres_documents_paths as $index => $path)
                                                            <a href="{{ Storage::url($path) }}" 
                                                               target="_blank"
                                                               class="text-blue-600 hover:text-blue-800 block">
                                                                Document supplémentaire {{ $index + 1 }}
                                                            </a>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
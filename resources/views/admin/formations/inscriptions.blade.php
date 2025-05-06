<x-admin-layout>
    <div class="container mx-auto px-6 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Gestion des Inscriptions</h2>
            <p class="mt-1 text-gray-600">Liste des inscriptions aux formations</p>
        </div>
        <a href="{{ route('admin.formations.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            <i class="fas fa-arrow-left mr-2"></i>Retour aux formations
        </a>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Participant
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Formation
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Date d'inscription
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Documents
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($inscriptions as $inscription)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="flex items-center">
                                <div>
                                    <p class="text-gray-900 whitespace-no-wrap font-medium">
                                        {{ $inscription->user->name }}
                                    </p>
                                    <p class="text-gray-600 whitespace-no-wrap">
                                        {{ $inscription->email }}
                                    </p>
                                    <p class="text-gray-600 whitespace-no-wrap">
                                        {{ $inscription->telephone }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div>
                                <p class="text-gray-900 whitespace-no-wrap font-medium">
                                    {{ $inscription->formation->titre }}
                                </p>
                                <p class="text-gray-600 whitespace-no-wrap">
                                    Du {{ $inscription->formation->date_debut->format('d/m/Y') }}
                                </p>
                                <p class="text-gray-600 whitespace-no-wrap">
                                    Au {{ $inscription->formation->date_fin->format('d/m/Y') }}
                                </p>
                            </div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $inscription->created_at->format('d/m/Y H:i') }}
                            </p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="space-y-2">
                                @if($inscription->acte_naissance_path)
                                    <a href="{{ Storage::url($inscription->acte_naissance_path) }}" 
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-800 block">
                                        <i class="fas fa-file-alt mr-2"></i>Acte de naissance
                                    </a>
                                @endif
                                @if($inscription->cni_path)
                                    <a href="{{ Storage::url($inscription->cni_path) }}" 
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-800 block">
                                        <i class="fas fa-id-card mr-2"></i>CNI
                                    </a>
                                @endif
                                @if($inscription->diplome_path)
                                    <a href="{{ Storage::url($inscription->diplome_path) }}" 
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-800 block">
                                        <i class="fas fa-graduation-cap mr-2"></i>Diplôme
                                    </a>
                                @endif
                                @if($inscription->autres_documents_paths)
                                    @foreach($inscription->autres_documents_paths as $document)
                                        <a href="{{ Storage::url($document) }}" 
                                           target="_blank"
                                           class="text-blue-600 hover:text-blue-800 block">
                                            <i class="fas fa-file mr-2"></i>Document supplémentaire
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="flex items-center space-x-4">
                                <button type="button" 
                                        class="text-green-600 hover:text-green-900"
                                        onclick="window.location.href='mailto:{{ $inscription->email }}'">
                                    <i class="fas fa-envelope"></i>
                                </button>
                                <form action="{{ route('admin.formations.inscriptions.destroy', $inscription) }}" 
                                      method="POST" 
                                      class="inline" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette inscription ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                            Aucune inscription trouvée.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">            {{ $inscriptions->links() }}
        </div>
    </div>
    </div>
</x-admin-layout>
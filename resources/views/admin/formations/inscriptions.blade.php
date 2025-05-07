@extends('layouts.admin')

@section('content')
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

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Formation
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Participant
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Contact
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Documents
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Date d'inscription
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inscriptions as $inscription)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <div class="flex items-center">
                                    @if($inscription->formation->image)
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                 src="{{ Storage::url($inscription->formation->image) }}"
                                                 alt="{{ $inscription->formation->titre }}">
                                        </div>
                                    @endif
                                    <div class="ml-3">
                                        <p class="text-gray-900 whitespace-no-wrap font-medium">
                                            {{ $inscription->formation->titre }}
                                        </p>
                                        <p class="text-gray-600 whitespace-no-wrap">
                                            {{ \Carbon\Carbon::parse($inscription->formation->date_debut)->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ $inscription->nom }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ $inscription->email }}</p>
                                <p class="text-gray-600 whitespace-no-wrap">{{ $inscription->telephone }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <div class="flex flex-col space-y-1">
                                    @if($inscription->acte_naissance_path)
                                        <a href="{{ Storage::url($inscription->acte_naissance_path) }}" 
                                           target="_blank"
                                           class="document-link text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-file-alt mr-2"></i>Acte de naissance
                                        </a>
                                    @endif
                                    @if($inscription->cni_path)
                                        <a href="{{ Storage::url($inscription->cni_path) }}" 
                                           target="_blank"
                                           class="document-link text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-id-card mr-2"></i>CNI
                                        </a>
                                    @endif
                                    @if($inscription->diplome_path)
                                        <a href="{{ Storage::url($inscription->diplome_path) }}" 
                                           target="_blank"
                                           class="document-link text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-graduation-cap mr-2"></i>Diplôme
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ $inscription->created_at->format('d/m/Y H:i') }}
                                </p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                <form action="{{ route('admin.formations.inscriptions.destroy', $inscription) }}" 
                                      method="POST"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette inscription ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-5 bg-white text-sm text-center">
                                Aucune inscription trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
                {{ $inscriptions->links() }}
            </div>
        </div>
    </div>
@endsection
<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">{{ __('Liste des onduleurs') }}</h1>
            <a href="{{ route('onduleurs.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Ajouter un onduleur') }}
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white shadow-md rounded my-6">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">{{ __('Modèle') }}</th>
                        <th class="py-3 px-6 text-left">{{ __('Numéro de série') }}</th>
                        <th class="py-3 px-6 text-center">{{ __('Status') }}</th>
                        <th class="py-3 px-6 text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @forelse($onduleurs as $onduleur)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left whitespace-nowrap">
                                {{ $onduleur->modele }}
                            </td>
                            <td class="py-3 px-6 text-left">
                                {{ $onduleur->numero_serie }}
                            </td>
                            <td class="py-3 px-6 text-center">
                                <span class="bg-{{ $onduleur->est_connecte ? 'green' : 'red' }}-200 text-{{ $onduleur->est_connecte ? 'green' : 'red' }}-600 py-1 px-3 rounded-full text-xs">
                                    {{ $onduleur->est_connecte ? __('Connecté') : __('Déconnecté') }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center">
                                    <a href="{{ route('onduleurs.show', $onduleur->id) }}" class="text-blue-600 hover:text-blue-900 mx-2">
                                        {{ __('Voir') }}
                                    </a>
                                    <a href="{{ route('onduleurs.edit', $onduleur->id) }}" class="text-yellow-600 hover:text-yellow-900 mx-2">
                                        {{ __('Modifier') }}
                                    </a>
                                    <form action="{{ route('onduleurs.destroy', $onduleur->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 mx-2" onclick="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cet onduleur ?') }}')">
                                            {{ __('Supprimer') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-3 px-6 text-center">
                                {{ __('Aucun onduleur trouvé') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Messages administratifs
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('admin.messages.create') }}" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                    Nouveau message
                </a>
            </div>

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($messages->isEmpty())
                        <p>Aucun message envoyé.</p>
                    @else
                        <table class="min-w-full">
                            <thead>
                                <tr class="text-left bg-gray-100">
                                    <th class="px-6 py-3">Destinataire</th>
                                    <th class="px-6 py-3">Sujet</th>
                                    <th class="px-6 py-3">Date d'envoi</th>
                                    <th class="px-6 py-3">Pièces jointes</th>
                                    <th class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($messages as $message)
                                    <tr class="border-b">
                                        <td class="px-6 py-4">
                                            {{ $message->recipient->name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $message->subject }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $message->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $message->attachments->count() }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('admin.messages.show', $message) }}" class="text-blue-600 hover:text-blue-800">
                                                Voir
                                            </a>
                                            <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ml-2 text-red-600 hover:text-red-800" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $messages->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

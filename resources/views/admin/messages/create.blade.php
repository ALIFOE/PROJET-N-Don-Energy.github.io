<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Nouveau message
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.messages.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="recipient_id" class="block mb-2 text-sm font-bold text-gray-700">Destinataire</label>
                            <select name="recipient_id" id="recipient_id" class="w-full px-3 py-2 border rounded shadow appearance-none" required>
                                <option value="">Sélectionner un destinataire</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            @error('recipient_id')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="subject" class="block mb-2 text-sm font-bold text-gray-700">Sujet</label>
                            <input type="text" name="subject" id="subject" class="w-full px-3 py-2 border rounded shadow appearance-none" required>
                            @error('subject')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="content" class="block mb-2 text-sm font-bold text-gray-700">Message</label>
                            <textarea name="content" id="content" rows="6" class="w-full px-3 py-2 border rounded shadow appearance-none" required></textarea>
                            @error('content')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="attachments" class="block mb-2 text-sm font-bold text-gray-700">Pièces jointes</label>
                            <input type="file" name="attachments[]" id="attachments" multiple class="w-full px-3 py-2 border rounded shadow appearance-none">
                            <p class="mt-1 text-xs text-gray-500">Vous pouvez sélectionner plusieurs fichiers. Taille maximale : 10MB par fichier</p>
                            @error('attachments.*')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <a href="{{ route('admin.messages.index') }}" class="text-gray-600 hover:text-gray-900">
                                Annuler
                            </a>
                            <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                                Envoyer le message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

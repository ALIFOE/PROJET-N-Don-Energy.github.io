<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Succès !</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="form-card">
                <h1 class="text-3xl font-bold mb-8 text-center">Finaliser votre inscription</h1>

                <form action="{{ route('soumettre.documents', $inscription->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="document" class="block text-gray-700 font-medium">Téléverser votre document</label>
                        <input type="file" id="document" name="document" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                        @error('document')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn-submit">Soumettre</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="py-12 bg-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h1 class="text-2xl font-bold mb-6">Complétez votre inscription</h1>

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @foreach ($formations as $formation)
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">{{ $formation->nom }}</h2>

                        <form action="{{ route('formation.submit-completion', $formation) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-4">
                                <label for="nom_{{ $formation->id }}" class="block text-gray-700 font-medium">Nom complet</label>
                                <input type="text" id="nom_{{ $formation->id }}" name="nom" class="w-full border-gray-300 rounded-lg shadow-sm" value="{{ request('email') }}" readonly>
                            </div>

                            <div class="mb-4">
                                <label for="acte_naissance_{{ $formation->id }}" class="block text-gray-700 font-medium">Acte de naissance</label>
                                <input type="file" id="acte_naissance_{{ $formation->id }}" name="acte_naissance" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                            </div>

                            <div class="mb-4">
                                <label for="cni_{{ $formation->id }}" class="block text-gray-700 font-medium">Carte d'identité nationale</label>
                                <input type="file" id="cni_{{ $formation->id }}" name="cni" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                            </div>

                            <div class="mb-4">
                                <label for="diplome_{{ $formation->id }}" class="block text-gray-700 font-medium">Dernier diplôme</label>
                                <input type="file" id="diplome_{{ $formation->id }}" name="diplome" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                            </div>

                            <div class="mb-4">
                                <label for="autres_documents_{{ $formation->id }}" class="block text-gray-700 font-medium">Autres documents (optionnel)</label>
                                <input type="file" id="autres_documents_{{ $formation->id }}" name="autres_documents[]" class="w-full border-gray-300 rounded-lg shadow-sm" multiple>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300">Soumettre</button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>

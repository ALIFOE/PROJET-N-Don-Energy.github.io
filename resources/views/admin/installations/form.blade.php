@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            {{ isset($installation) ? 'Modifier l\'installation' : 'Nouvelle installation' }}
        </h2>
        <a href="{{ route('admin.installations.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ isset($installation) ? route('admin.installations.update', $installation) : route('admin.installations.store') }}" 
              method="POST" 
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @if(isset($installation))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">Client</label>
                    <select name="user_id" 
                            id="user_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required>
                        <option value="">Sélectionner un client</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ (old('user_id', $installation->user_id ?? '') == $user->id) ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type_installation" class="block text-sm font-medium text-gray-700">Type d'installation</label>
                    <select name="type_installation" 
                            id="type_installation" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required>
                        <option value="residentiel" {{ (old('type_installation', $installation->type_installation ?? '') === 'residentiel') ? 'selected' : '' }}>Résidentiel</option>
                        <option value="commercial" {{ (old('type_installation', $installation->type_installation ?? '') === 'commercial') ? 'selected' : '' }}>Commercial</option>
                        <option value="industriel" {{ (old('type_installation', $installation->type_installation ?? '') === 'industriel') ? 'selected' : '' }}>Industriel</option>
                    </select>
                    @error('type_installation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="puissance" class="block text-sm font-medium text-gray-700">Puissance (kWc)</label>
                    <input type="number" 
                           name="puissance" 
                           id="puissance" 
                           step="0.01" 
                           min="0" 
                           value="{{ old('puissance', $installation->puissance ?? '') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                    @error('puissance')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_installation" class="block text-sm font-medium text-gray-700">Date d'installation</label>
                    <input type="date" 
                           name="date_installation" 
                           id="date_installation" 
                           value="{{ old('date_installation', isset($installation) ? $installation->date_installation->format('Y-m-d') : '') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                    @error('date_installation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
                    <input type="text" 
                           name="adresse" 
                           id="adresse" 
                           value="{{ old('adresse', $installation->adresse ?? '') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                    @error('adresse')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="code_postal" class="block text-sm font-medium text-gray-700">Code postal</label>
                    <input type="text" 
                           name="code_postal" 
                           id="code_postal" 
                           value="{{ old('code_postal', $installation->code_postal ?? '') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                    @error('code_postal')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="ville" class="block text-sm font-medium text-gray-700">Ville</label>
                    <input type="text" 
                           name="ville" 
                           id="ville" 
                           value="{{ old('ville', $installation->ville ?? '') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                    @error('ville')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                    <select name="statut" 
                            id="statut" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required>
                        <option value="en_attente" {{ (old('statut', $installation->statut ?? '') === 'en_attente') ? 'selected' : '' }}>En attente</option>
                        <option value="en_cours" {{ (old('statut', $installation->statut ?? '') === 'en_cours') ? 'selected' : '' }}>En cours</option>
                        <option value="terminee" {{ (old('statut', $installation->statut ?? '') === 'terminee') ? 'selected' : '' }}>Terminée</option>
                        <option value="annulee" {{ (old('statut', $installation->statut ?? '') === 'annulee') ? 'selected' : '' }}>Annulée</option>
                    </select>
                    @error('statut')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="documents" class="block text-sm font-medium text-gray-700">Documents</label>
                <input type="file" 
                       name="documents[]" 
                       id="documents" 
                       multiple
                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="mt-1 text-sm text-gray-500">
                    Vous pouvez sélectionner plusieurs fichiers. Formats acceptés : PDF, DOC, DOCX, JPG, JPEG, PNG
                </p>
                @error('documents')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @if(isset($installation) && $installation->documents)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Documents actuels :</h4>
                        <div class="space-y-2">
                            @foreach($installation->documents as $document)
                                <div class="flex items-center justify-between">
                                    <a href="{{ Storage::url($document) }}" 
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-file mr-2"></i>{{ basename($document) }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex justify-end space-x-4">
                <button type="reset" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Réinitialiser
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    {{ isset($installation) ? 'Mettre à jour' : 'Créer' }}
                </button>
            </div>
        </form>
    </div>
@endsection
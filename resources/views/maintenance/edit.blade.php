@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6">Modifier la maintenance</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ isset($maintenance) && is_object($maintenance) ? route('maintenance.update', $maintenance->id) : '#' }}">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="installation">
                        Installation <span class="text-red-500">*</span>
                    </label>
                    <select name="installation_id" id="installation" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('installation_id') border-red-500 @enderror">
                        @foreach($installations as $installation)
                            <option value="{{ $installation->id }}" {{ $maintenance->installation_id == $installation->id ? 'selected' : '' }}>
                                {{ $installation->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="type">
                        Type de maintenance <span class="text-red-500">*</span>
                    </label>
                    <select name="type" id="type" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('type') border-red-500 @enderror">
                        <option value="preventive" {{ $maintenance->type === 'preventive' ? 'selected' : '' }}>Préventive</option>
                        <option value="corrective" {{ $maintenance->type === 'corrective' ? 'selected' : '' }}>Corrective</option>
                        <option value="predictive" {{ $maintenance->type === 'predictive' ? 'selected' : '' }}>Prédictive</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="date">
                        Date prévue <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date_prevue" id="date" required
                        value="{{ old('date_prevue', $maintenance->date->format('Y-m-d')) }}"
                        min="{{ date('Y-m-d') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('date_prevue') border-red-500 @enderror">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="4" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror"
                        placeholder="Décrivez les détails de la maintenance...">{{ old('description', $maintenance->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="statut">
                        Statut <span class="text-red-500">*</span>
                    </label>
                    <select name="statut" id="statut" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('statut') border-red-500 @enderror">
                        <option value="planifiee" {{ $maintenance->statut === 'planifiee' ? 'selected' : '' }}>Planifiée</option>
                        <option value="en_cours" {{ $maintenance->statut === 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="terminee" {{ $maintenance->statut === 'terminee' ? 'selected' : '' }}>Terminée</option>
                        <option value="annulee" {{ $maintenance->statut === 'annulee' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="notes">
                        Notes additionnelles
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="Notes optionnelles...">{{ old('notes', $maintenance->notes) }}</textarea>
                </div>

                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('maintenance-predictive') }}" 
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Annuler
                    </a>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Mettre à jour
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
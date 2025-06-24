@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded bg-green-100 text-green-800 border border-green-300">
                        {{ session('success') }}
                    </div>
                @endif
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">{{ $service->nom }}</h2>

                <form action="{{ route('services.request.submit', $service) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700">Nom complet</label>
                        <input type="text" name="nom" id="nom" value="{{ old('nom', auth()->user()->name) }}" required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('nom')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                        <input type="tel" name="telephone" id="telephone" value="{{ old('telephone') }}" required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('telephone')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description de votre projet</label>
                        <textarea name="description" id="description" rows="4" required 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($service->champs_supplementaires)
                        @foreach($service->champs_supplementaires as $champ)
                            <div>
                                <label for="{{ $champ['nom'] }}" class="block text-sm font-medium text-gray-700">{{ $champ['label'] }}</label>
                                @if($champ['type'] === 'text')
                                    <input type="text" name="details[{{ $champ['nom'] }}]" id="{{ $champ['nom'] }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           {{ $champ['required'] ? 'required' : '' }}>
                                @elseif($champ['type'] === 'select')
                                    <select name="details[{{ $champ['nom'] }}]" id="{{ $champ['nom'] }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            {{ $champ['required'] ? 'required' : '' }}>
                                        @foreach($champ['options'] as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        @endforeach
                    @endif

                    <div class="flex justify-end space-x-4">                        <a href="{{ route('services.index') }}" 
                           class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Envoyer la demande
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

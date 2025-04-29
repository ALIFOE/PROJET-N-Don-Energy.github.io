@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Configuration des onduleurs</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif

        @foreach($onduleurs as $onduleur)
            <div class="bg-white shadow rounded-lg mb-6 p-6">
                <h2 class="text-xl font-semibold mb-4">{{ $onduleur->marque }} - {{ $onduleur->modele }}</h2>
                <p class="text-gray-600 mb-4">N° Série: {{ $onduleur->numero_serie }}</p>

                <form action="{{ route('onduleur.config.save') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="onduleur_id" value="{{ $onduleur->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Adresse IP</label>
                            <input type="text" name="parametres[ip_address]" 
                                value="{{ $onduleur->ip_address }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                pattern="^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Port</label>
                            <input type="number" name="parametres[port]" 
                                value="{{ $onduleur->port }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                min="1" max="65535">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Protocole</label>
                            <select name="parametres[protocole]" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="modbus_tcp" {{ $onduleur->protocole === 'modbus_tcp' ? 'selected' : '' }}>Modbus TCP</option>
                                <option value="sunspec" {{ $onduleur->protocole === 'sunspec' ? 'selected' : '' }}>SunSpec</option>
                                <option value="rest_api" {{ $onduleur->protocole === 'rest_api' ? 'selected' : '' }}>REST API</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Intervalle de lecture (secondes)</label>
                            <input type="number" name="parametres[intervalle_lecture]" 
                                value="{{ $onduleur->intervalle_lecture ?? 300 }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                min="60" max="3600">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Sauvegarder
                        </button>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold mb-6">Message de {{ $message->name }}</h2>

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nom</label>
                <p class="text-gray-900">{{ $message->name }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <p class="text-gray-900">{{ $message->email }}</p>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Message</label>
                <p class="text-gray-900 whitespace-pre-wrap">{{ $message->message }}</p>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('messages.edit', $message) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Modifier
                </a>
                <form action="{{ route('messages.destroy', $message) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')">
                        Supprimer
                    </button>
                </form>
                <a href="{{ route('messages.index') }}" class="text-gray-500 hover:text-gray-700">
                    Retour
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

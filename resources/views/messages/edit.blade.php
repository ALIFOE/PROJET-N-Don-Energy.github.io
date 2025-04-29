@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold mb-6">Modifier le Message</h2>

        <form action="{{ route('messages.update', $message) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nom</label>                <input type="text" name="name" id="name" value="{{ old('name', $message->name) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs italic">{{ $errors->first('name') }}</p>
                @enderror
            </div>

            <div class="mb-4">                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $message->email) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-xs italic">{{ $errors->first('email') }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="message" class="block text-gray-700 text-sm font-bold mb-2">Message</label>                <textarea name="message" id="message" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('message') border-red-500 @enderror">{{ old('message', $message->message) }}</textarea>
                @error('message')
                    <p class="text-red-500 text-xs italic">{{ $errors->first('message') }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Mettre à jour
                </button>
                <a href="{{ route('messages.index') }}" class="text-gray-500 hover:text-gray-700">
                    Retour
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

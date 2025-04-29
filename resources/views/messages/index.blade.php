@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Messages</h2>
        <a href="{{ route('messages.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Nouveau Message
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded my-6">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Nom</th>
                    <th class="py-3 px-6 text-left">Email</th>
                    <th class="py-3 px-6 text-left">Message</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach($messages as $message)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">{{ $message->name }}</td>
                    <td class="py-3 px-6 text-left">{{ $message->email }}</td>
                    <td class="py-3 px-6 text-left">{{ Str::limit($message->message, 50) }}</td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                            <a href="{{ route('messages.show', $message) }}" class="text-blue-500 hover:text-blue-700 mx-2">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('messages.edit', $message) }}" class="text-yellow-500 hover:text-yellow-700 mx-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('messages.destroy', $message) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 mx-2" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

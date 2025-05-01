<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Détails du message
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <a href="{{ route('admin.messages.index') }}" class="text-blue-600 hover:text-blue-800">
                            &larr; Retour aux messages
                        </a>
                    </div>

                    <div class="mb-6">
                        <h3 class="mb-2 text-lg font-semibold">{{ $message->subject }}</h3>
                        <p class="text-sm text-gray-600">
                            Envoyé à : {{ $message->recipient->name }} ({{ $message->recipient->email }})
                            <br>
                            Date : {{ $message->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <div class="p-4 mb-6 bg-gray-50 rounded">
                        {!! nl2br(e($message->content)) !!}
                    </div>

                    @if($message->attachments->count() > 0)
                        <div class="mt-6">
                            <h4 class="mb-2 font-semibold">Pièces jointes :</h4>
                            <ul class="list-disc list-inside">
                                @foreach($message->attachments as $attachment)
                                    <li class="mb-2">
                                        <a href="{{ Storage::url($attachment->file_path) }}" 
                                           class="text-blue-600 hover:text-blue-800"
                                           target="_blank">
                                            {{ $attachment->file_name }}
                                        </a>
                                        <span class="text-sm text-gray-500">
                                            ({{ number_format($attachment->size / 1024, 2) }} KB)
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Journal d'activités
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="text-left bg-gray-100">
                                    <th class="px-6 py-3">Utilisateur</th>
                                    <th class="px-6 py-3">Action</th>
                                    <th class="px-6 py-3">Description</th>
                                    <th class="px-6 py-3">Adresse IP</th>
                                    <th class="px-6 py-3">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activites as $activite)
                                    <tr class="border-b">
                                        <td class="px-6 py-4">
                                            {{ $activite->user->name ?? 'Utilisateur supprimé' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $activite->action }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $activite->description }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $activite->ip_address }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $activite->created_at->format('d/m/Y H:i:s') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $activites->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

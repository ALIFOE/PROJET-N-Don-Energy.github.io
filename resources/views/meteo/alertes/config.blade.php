@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-6">Configuration des alertes météo</h2>

                <form method="POST" action="{{ route('meteo.alertes.save') }}">
                    @csrf

                    <!-- Seuils de température -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h2 class="text-xl font-semibold mb-4">Seuils de température</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="temp_min" class="block text-sm font-medium text-gray-700">Température minimale (°C)</label>
                                <input type="number" name="temp_min" id="temp_min" step="0.1" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('temp_min', $config['temp_min'] ?? '') }}">
                                <p class="mt-1 text-sm text-gray-500">Alerte si la température descend en dessous de ce seuil</p>
                            </div>
                            <div>
                                <label for="temp_max" class="block text-sm font-medium text-gray-700">Température maximale (°C)</label>
                                <input type="number" name="temp_max" id="temp_max" step="0.1"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('temp_max', $config['temp_max'] ?? '') }}">
                                <p class="mt-1 text-sm text-gray-500">Alerte si la température dépasse ce seuil</p>
                            </div>
                        </div>
                    </div>

                    <!-- Alertes de vent -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h2 class="text-xl font-semibold mb-4">Alertes de vent</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="wind_speed" class="block text-sm font-medium text-gray-700">Vitesse du vent (km/h)</label>
                                <input type="number" name="wind_speed" id="wind_speed" min="0" step="1"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('wind_speed', $config['wind_speed'] ?? '') }}">
                                <p class="mt-1 text-sm text-gray-500">Alerte si la vitesse du vent dépasse ce seuil</p>
                            </div>
                        </div>
                    </div>

                    <!-- Alertes de pluie -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h2 class="text-xl font-semibold mb-4">Alertes de pluie</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="rain_probability" class="block text-sm font-medium text-gray-700">Probabilité de pluie (%)</label>
                                <input type="number" name="rain_probability" id="rain_probability" min="0" max="100" step="1"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('rain_probability', $config['rain_probability'] ?? '') }}">
                                <p class="mt-1 text-sm text-gray-500">Alerte si la probabilité de pluie dépasse ce seuil</p>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h2 class="text-xl font-semibold mb-4">Notifications</h2>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="notify_email" id="notify_email" 
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                    {{ old('notify_email', $config['notify_email'] ?? false) ? 'checked' : '' }}>
                                <label for="notify_email" class="ml-2 block text-sm text-gray-700">Recevoir les alertes par email</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="notify_sms" id="notify_sms"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                    {{ old('notify_sms', $config['notify_sms'] ?? false) ? 'checked' : '' }}>
                                <label for="notify_sms" class="ml-2 block text-sm text-gray-700">Recevoir les alertes par SMS</label>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="window.history.back()"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                            Annuler
                        </button>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                            Enregistrer les paramètres
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

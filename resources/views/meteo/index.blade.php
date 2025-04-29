@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Météo Actuelle</h1>
    
    <div class="bg-white shadow-lg rounded-lg p-6" id="meteo-data">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <h3 class="text-lg font-semibold mb-2">Température</h3>
                <p class="text-2xl" id="temperature">Chargement...</p>
            </div>
            <div class="text-center">
                <h3 class="text-lg font-semibold mb-2">Vitesse du Vent</h3>
                <p class="text-2xl" id="wind-speed">Chargement...</p>
            </div>
            <div class="text-center">
                <h3 class="text-lg font-semibold mb-2">Probabilité de Pluie</h3>
                <p class="text-2xl" id="rain-probability">Chargement...</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateMeteoData() {
        fetch('/meteo/donnees-actuelles')
            .then(response => response.json())
            .then(data => {
                document.getElementById('temperature').textContent = `${data.temperature}°C`;
                document.getElementById('wind-speed').textContent = `${data.wind_speed} km/h`;
                document.getElementById('rain-probability').textContent = `${data.rain_probability}%`;
            })
            .catch(error => console.error('Erreur lors de la récupération des données météo:', error));
    }

    updateMeteoData();
    // Mise à jour toutes les 5 minutes
    setInterval(updateMeteoData, 5 * 60 * 1000);
});
</script>
@endpush
@endsection

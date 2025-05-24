<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-bold mb-6">Production en Temps Réel</h2>

                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="h-[400px]">
                            <canvas id="realtimeChart"></canvas>
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-gray-700">Production Actuelle</h3>
                            <p class="text-3xl font-bold text-green-600" id="currentProduction">--</p>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-gray-700">Consommation Actuelle</h3>
                            <p class="text-3xl font-bold text-orange-600" id="currentConsumption">--</p>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-gray-700">Bilan</h3>
                            <p class="text-3xl font-bold text-blue-600" id="balance">--</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="module" src="{{ asset('js/realtime-monitoring.js') }}"></script>
    @endpush
</x-app-layout>

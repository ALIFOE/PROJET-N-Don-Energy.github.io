<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-8 text-center">Rapports et Analyses</h1>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Rapports Disponibles -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-xl font-semibold mb-4 text-gray-800">Rapports Disponibles</h2>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <h3 class="font-medium">Rapport de Production</h3>
                                        <p class="text-sm text-gray-600">Production d'énergie détaillée</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                            <i class="fas fa-file-pdf mr-2"></i>PDF
                                        </button>
                                        <button class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                            <i class="fas fa-file-excel mr-2"></i>Excel
                                        </button>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <h3 class="font-medium">Rapport de Performance</h3>
                                        <p class="text-sm text-gray-600">Analyse des performances du système</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                            <i class="fas fa-file-pdf mr-2"></i>PDF
                                        </button>
                                        <button class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                            <i class="fas fa-file-excel mr-2"></i>Excel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Analyses Personnalisées -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-xl font-semibold mb-4 text-gray-800">Analyses Personnalisées</h2>
                            <form class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Période</label>
                                    <div class="flex space-x-2 mt-1">
                                        <input type="date" class="flex-1 rounded-md border-gray-300">
                                        <input type="date" class="flex-1 rounded-md border-gray-300">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Type d'Analyse</label>
                                    <select class="mt-1 block w-full rounded-md border-gray-300">
                                        <option>Production d'énergie</option>
                                        <option>Rendement</option>
                                        <option>Comparaison</option>
                                        <option>Impact environnemental</option>
                                    </select>
                                </div>
                                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700">
                                    Générer le Rapport
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

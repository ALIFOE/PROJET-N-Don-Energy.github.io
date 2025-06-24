@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Nos Formations</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-8">
                Découvrez nos formations complètes en énergie solaire photovoltaïque et développez vos compétences avec des experts du domaine.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <span class="text-2xl font-bold text-blue-600 block">{{ $formations->where('niveau', 'débutant')->count() }}</span>
                    <span class="text-gray-600">Formations Débutant</span>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <span class="text-2xl font-bold text-blue-600 block">{{ $formations->count() }}</span>
                    <span class="text-gray-600">Formations</span>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <span class="text-2xl font-bold text-blue-600 block">{{ $formations->where('niveau', 'avancé')->count() }}</span>
                    <span class="text-gray-600">Formations Avancées</span>
                </div>
            </div>
        </div>

        <div class="text-center mb-8">
            <p class="text-gray-600">Centre de Formation Agréé</p>
            <p>Numéro d'agrément: 11756789100</p>
        </div>

        <!-- Badges de certification -->
        <div class="flex justify-center gap-8 mb-16">
            <div class="text-center">
                <img src="{{ asset('images/certif-1.png') }}" alt="Certification Qualibat" class="h-20 mx-auto mb-2">
                <p class="text-sm text-gray-600">Certifié Qualibat</p>
            </div>
            <div class="text-center">
                <img src="{{ asset('images/certif-2.png') }}" alt="Certification QualiPV" class="h-20 mx-auto mb-2">
                <p class="text-sm text-gray-600">Certifié QualiPV</p>
            </div>
        </div>

        <!-- Grille des formations -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($formations as $formation)
            <div class="bg-white rounded-xl shadow-xl overflow-hidden transform hover:scale-105 transition duration-300">
                <div class="relative">
                    @if($formation->image)
                        <img src="{{ Storage::url($formation->image) }}" alt="{{ $formation->titre }}" class="w-full h-56 object-cover">
                    @else
                        <img src="{{ asset('images/default-formation.jpg') }}" alt="{{ $formation->titre }}" class="w-full h-56 object-cover">
                    @endif
                    <div class="absolute top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-full">
                        {{ \Carbon\Carbon::parse($formation->date_debut)->diffInMonths($formation->date_fin) }} Mois
                    </div>
                    <div class="absolute bottom-4 left-4 bg-blue-500 text-white px-4 py-1 rounded-full text-sm">
                        {{ ucfirst($formation->niveau ?? 'Tous niveaux') }}
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-900">{{ $formation->titre }}</h3>
                        <span class="text-2xl font-bold text-blue-600">{{ number_format($formation->prix, 0, ',', ' ') }}CFA</span>
                    </div>
                    <p class="text-gray-600 mb-6">{{ Str::limit($formation->description, 500) }}</p>
                    <div class="space-y-3 mb-6">
                        @if($formation->prerequis)
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <span>{{ Str::limit($formation->prerequis, 100) }}</span>
                        </div>
                        @endif
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-calendar text-green-500 mr-3"></i>
                            <span> {{ \Carbon\Carbon::parse($formation->date_debut)->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-users text-green-500 mr-3"></i>
                            <span>{{ $formation->places_disponibles - $formation->inscriptions->count() }} places restantes</span>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        @if($formation->places_disponibles > $formation->inscriptions->count() && \Carbon\Carbon::parse($formation->date_debut)->isFuture())
                            <a href="{{ route('inscription') }}" class="flex-1 text-center bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition">
                                S'inscrire
                            </a>
                        @else
                            <button disabled class="flex-1 text-center bg-gray-400 text-white py-3 rounded-lg cursor-not-allowed">
                                Complet
                            </button>
                        @endif
                        @if($formation->flyer)
                            <a href="{{ route('formation.flyer.download', $formation) }}" class="flex items-center justify-center px-4 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                <i class="fas fa-download mr-2"></i>
                                Programme
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12">
                <h3 class="text-xl font-semibold text-gray-600">Aucune formation disponible pour le moment</h3>
                <p class="text-gray-500 mt-2">Revenez bientôt pour découvrir nos nouvelles formations</p>
            </div>
            @endforelse
        </div>

        <!-- Section Avantages -->
        <div class="mt-24">
            <h2 class="text-3xl font-bold text-center mb-12">Pourquoi choisir N'CREFER ?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <i class="fas fa-medal text-4xl text-blue-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-3">Expertise Reconnue</h3>
                    <p class="text-gray-600">Plus de 10 ans d'expérience dans la formation aux énergies renouvelables</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <i class="fas fa-user-tie text-4xl text-blue-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-3">Formateurs Qualifiés</h3>
                    <p class="text-gray-600">Une équipe de professionnels expérimentés et passionnés</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <i class="fas fa-certificate text-4xl text-blue-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-3">Certifications Reconnues</h3>
                    <p class="text-gray-600">Formations certifiantes reconnues par l'État et les professionnels</p>
                </div>
            </div>
        </div>

        <!-- Section FAQ -->
        <div class="mt-24">
            <h2 class="text-3xl font-bold text-center mb-12">Questions Fréquentes</h2>
            <div class="max-w-3xl mx-auto space-y-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <button class="flex justify-between items-center w-full">
                        <span class="text-lg font-semibold">Quels sont les prérequis pour suivre ces formations ?</span>
                        <i class="fas fa-chevron-down text-blue-600"></i>
                    </button>
                    <div class="mt-4 text-gray-600">
                        Chaque formation a des prérequis différents. Les formations débutantes sont accessibles à tous, tandis que les niveaux intermédiaire et avancé nécessitent une expérience préalable dans le domaine.
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <button class="flex justify-between items-center w-full">
                        <span class="text-lg font-semibold">Les formations sont-elles certifiantes ?</span>
                        <i class="fas fa-chevron-down text-blue-600"></i>
                    </button>
                    <div class="mt-4 text-gray-600">
                        Oui, toutes nos formations sont certifiantes et reconnues par les professionnels du secteur. Vous recevrez un certificat à la fin de chaque formation.
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <button class="flex justify-between items-center w-full">
                        <span class="text-lg font-semibold">Comment se déroule l'inscription ?</span>
                        <i class="fas fa-chevron-down text-blue-600"></i>
                    </button>
                    <div class="mt-4 text-gray-600">
                        L'inscription se fait en ligne via notre plateforme. Après validation de votre inscription, vous recevrez une confirmation par email avec tous les détails pratiques.
                    </div>
                </div>
            </div>
        </div>

        <!-- Call-to-action final -->
        <div class="mt-24 text-center bg-blue-600 rounded-2xl p-12 relative overflow-hidden">
            <div class="absolute inset-0 bg-blue-700 opacity-50 pattern-dots"></div>
            <div class="relative z-10">
                <h2 class="text-3xl font-bold text-white mb-6">Prêt à développer vos compétences ?</h2>
                <p class="text-blue-100 mb-4 text-lg">Inscrivez-vous maintenant et bénéficiez de -10% sur votre première formation</p>
                <p class="text-blue-100 mb-8">
                    <i class="fas fa-phone-alt mr-2"></i> +228 97 73 43 81
                    <span class="mx-4">|</span>
                    <i class="fas fa-envelope mr-2"></i> crefer@gmail.com
                </p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('inscription') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg hover:bg-blue-50 transition">
                        S'inscrire maintenant
                    </a>
                    <a href="{{ route('contact') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition">
                        Nous contacter
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

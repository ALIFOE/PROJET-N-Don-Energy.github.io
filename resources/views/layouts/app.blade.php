<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CREFER') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
      <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Styles supplémentaires -->
    @stack('styles')

    <style>
        .notification-success {
            background-color: rgba(255, 165, 0, 0.1);
            border-color: var(--primary-color, #FFA500);
            color: var(--primary-color, #FFA500);
        }
        .notification-error {
            background-color: #000000 #FEE2E2;
            border-color: #EF4444;
            color: #B91C1C;
        }
        .notification-info {
            background-color: rgba(0, 0, 255, 0.1);
            border-color: var(--accent-color, #0000FF);
            color: var(--accent-color, #0000FF);
        }
        .footer {
            background-color: var(--dark-color, #000000);
            color: var(--secondary-color, #FFFFFF);
        }
        .footer-link {
            color: var(--secondary-color, #FFFFFF);
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }
        .footer-link:hover {
            opacity: 1;
        }
        .footer-icon {
            color: var(--primary-color, #FFA500);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @if(auth()->check() && auth()->user()->is_admin)
            @include('layouts.admin-navigation')
        @elseif(!auth()->check() || !auth()->user()->is_admin)
            @include('layouts.navigation')
        @endif

        <!-- Système de notification -->
        <div id="notification" class="fixed top-4 right-4 z-50 transform transition-transform duration-300 ease-in-out translate-x-full">
            <div class="max-w-sm rounded-lg shadow-lg p-4">
                <div class="flex items-center">
                    <div id="notificationIcon" class="flex-shrink-0 w-8 h-8 mr-3 flex items-center justify-center rounded-full"></div>
                    <div class="flex-1">
                        <h3 id="notificationTitle" class="font-medium"></h3>
                        <p id="notificationMessage" class="text-sm"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif
        
        <!-- Page Content -->
        <main class="pt-16">
            @yield('content')
            {{ $slot ?? '' }}
        </main>

        <!-- Footer -->
        @if(!request()->is('admin*'))        <footer class="footer mt-auto py-8">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-4 gap-8">
                    <div>
                        <div class="flex items-center mb-4">
                            <i class="fas fa-solar-panel footer-icon text-3xl mr-2"></i>
                            <span class="text-2xl font-bold">CREFER</span>
                        </div>
                        <p class="text-gray-400">La solution complète pour le suivi et l'optimisation de vos installations solaires photovoltaïques.</p>
                        <div class="flex mt-4 space-x-4">
                            <a href="https://facebook.com/CREFER" target="_blank" class="footer-link">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/CREFER" target="_blank" class="footer-link">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://linkedin.com/company/CREFER" target="_blank" class="footer-link">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://instagram.com/CREFER" target="_blank" class="footer-link">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Liens rapides</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('home') }}" class="footer-link">Accueil</a></li>
                            <li><a href="{{ route('fonctionnalite') }}" class="footer-link">Fonctionnalités</a></li>
                            <li><a href="{{ route('formation') }}" class="footer-link">Formations</a></li>
                            <li><a href="{{ route('about') }}" class="footer-link">À propos</a></li>
                            <li><a href="{{ route('contact') }}" class="footer-link">Contact</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Services</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('suivi-production') }}" class="footer-link">Suivi de production</a></li>
                            <li><a href="{{ route('maintenance-predictive') }}" class="footer-link">Maintenance prédictive</a></li>
                            <li><a href="{{ route('optimisation') }}" class="footer-link">Optimisation de rendement</a></li>
                            <li><a href="{{ route('support') }}" class="footer-link">Assistance technique</a></li>
                            <li><a href="{{ route('formation') }}" class="footer-link">Formation</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Contact</h3>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <i class="fas fa-map-marker-alt mt-1 mr-2 footer-icon"></i>
                                <span class="footer-link">Rue KOPEGA 56.GB Lomé, Togo</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-phone mt-1 mr-2 footer-icon"></i>
                                <span class="footer-link">+228 97 73 43 81</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-envelope mt-1 mr-2 footer-icon"></i>
                                <a href="mailto:contact@CREFER.com" class="footer-link">contact@CREFER.com</a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                    <p class="footer-link">&copy; {{ date('Y') }} CREFER. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
        @endif
    </div>

    <script>
        // Fonction notification
        function showNotification(type, title, message) {
            const notification = document.getElementById('notification');
            const notificationIcon = document.getElementById('notificationIcon');
            const notificationTitle = document.getElementById('notificationTitle');
            const notificationMessage = document.getElementById('notificationMessage');

            const styles = {
                success: {
                    class: 'notification-success',
                    icon: '<i class="fas fa-check-circle"></i>'
                },
                error: {
                    class: 'notification-error',
                    icon: '<i class="fas fa-exclamation-circle"></i>'
                },
                info: {
                    class: 'notification-info',
                    icon: '<i class="fas fa-info-circle"></i>'
                }
            };

            const style = styles[type];
            notification.firstElementChild.className = `max-w-sm rounded-lg shadow-lg p-4 ${style.class}`;
            notificationIcon.innerHTML = style.icon;
            
            notificationTitle.textContent = title;
            notificationMessage.textContent = message;
            
            notification.classList.remove('translate-x-full');
            
            setTimeout(() => {
                notification.classList.add('translate-x-full');
            }, 5000);
        }
    </script>

    @if(session('success'))
        <script>
            showNotification('success', 'Succès', '{{ session('success') }}');
        </script>
    @endif

    @if(session('error'))
        <script>
            showNotification('error', 'Erreur', '{{ session('error') }}');
        </script>
    @endif

    @if(session('info'))
        <script>
            showNotification('info', 'Information', '{{ session('info') }}');
        </script>
    @endif

    <!-- Scripts supplémentaires -->
    @stack('scripts')
</body>
</html>

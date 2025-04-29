<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NE DON ENERGY') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Styles supplémentaires -->
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Système de notification -->
        <div id="notification" class="fixed top-4 right-4 z-50 transform transition-transform duration-300 ease-in-out translate-x-full">
            <div class="max-w-sm bg-white rounded-lg shadow-lg p-4">
                <div class="flex items-center">
                    <div id="notificationIcon" class="flex-shrink-0 w-8 h-8 mr-3 flex items-center justify-center rounded-full"></div>
                    <div class="flex-1">
                        <h3 id="notificationTitle" class="font-medium text-gray-900"></h3>
                        <p id="notificationMessage" class="text-sm text-gray-600"></p>
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
        <footer class="bg-gray-800 text-white mt-auto py-8">
            <div class="container mx-auto px-6">
                <p class="text-center">&copy; {{ date('Y') }} Né Don Energy. Tous droits réservés.</p>
            </div>
        </footer>
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
                    bgColor: 'bg-green-100',
                    iconColor: 'text-green-500',
                    icon: '<i class="fas fa-check-circle"></i>'
                },
                error: {
                    bgColor: 'bg-red-100',
                    iconColor: 'text-red-500',
                    icon: '<i class="fas fa-exclamation-circle"></i>'
                },
                info: {
                    bgColor: 'bg-blue-100',
                    iconColor: 'text-blue-500',
                    icon: '<i class="fas fa-info-circle"></i>'
                }
            };

            const style = styles[type];
            notificationIcon.className = `flex-shrink-0 w-8 h-8 mr-3 flex items-center justify-center rounded-full ${style.bgColor} ${style.iconColor}`;
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
            showNotification('success', 'Succès', "{{ session('success') }}");
        </script>
    @endif

    @if(session('error'))
        <script>
            showNotification('error', 'Erreur', "{{ session('error') }}");
        </script>
    @endif

    @if(session('info'))
        <script>
            showNotification('info', 'Information', "{{ session('info') }}");
        </script>
    @endif

    <!-- Scripts supplémentaires -->
    @stack('scripts')
</body>
</html>

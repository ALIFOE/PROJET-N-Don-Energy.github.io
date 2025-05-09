@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-900">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg border border-gray-700">
            <div class="p-6">
                <h1 class="text-3xl font-bold mb-8 text-orange-400">Paramètres</h1>

                <!-- Paramètres du compte -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4 text-gray-300">Paramètres du compte</h2>
                    <div class="bg-gray-700 rounded-lg p-6 border border-gray-600">
                        <form action="{{ route('settings.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Informations personnelles -->
                                <div>
                                    <h3 class="font-semibold mb-4 text-gray-300">Informations personnelles</h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-300">Nom</label>
                                            <input type="text" name="name" id="name" value="{{ auth()->user()->name }}"
                                                class="mt-1 block w-full rounded-md bg-gray-800 border-gray-600 text-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200">
                                        </div>
                                        <div>
                                            <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                                            <input type="email" name="email" id="email" value="{{ auth()->user()->email }}"
                                                class="mt-1 block w-full rounded-md bg-gray-800 border-gray-600 text-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200">
                                        </div>
                                    </div>
                                </div>

                                <!-- Préférences de notification -->
                                <div>
                                    <h3 class="font-semibold mb-4 text-gray-300">Préférences de notification</h3>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="notifications[]" value="email" class="form-checkbox text-orange-500 bg-gray-800 border-gray-600">
                                            <span class="ml-2 text-gray-300">Notifications par email</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="notifications[]" value="sms" class="form-checkbox text-orange-500 bg-gray-800 border-gray-600">
                                            <span class="ml-2 text-gray-300">Notifications par SMS</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="notifications[]" value="browser" class="form-checkbox text-orange-500 bg-gray-800 border-gray-600">
                                            <span class="ml-2 text-gray-300">Notifications navigateur</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition duration-300">
                                    Sauvegarder les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Paramètres de sécurité -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4 text-gray-300">Sécurité</h2>
                    <div class="bg-gray-700 rounded-lg p-6 border border-gray-600">
                        <form action="{{ route('settings.security') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-300">Mot de passe actuel</label>
                                    <input type="password" name="current_password" id="current_password"
                                        class="mt-1 block w-full rounded-md bg-gray-800 border-gray-600 text-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200">
                                </div>
                                <div>
                                    <label for="new_password" class="block text-sm font-medium text-gray-300">Nouveau mot de passe</label>
                                    <input type="password" name="new_password" id="new_password"
                                        class="mt-1 block w-full rounded-md bg-gray-800 border-gray-600 text-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200">
                                </div>
                                <div>
                                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-300">Confirmer le nouveau mot de passe</label>
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                        class="mt-1 block w-full rounded-md bg-gray-800 border-gray-600 text-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200">
                                </div>
                            </div>

                            <div class="mt-6 space-y-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="enable_2fa" class="form-checkbox text-orange-500 bg-gray-800 border-gray-600">
                                    <span class="ml-2 text-gray-300">Activer l'authentification à deux facteurs</span>
                                </label>

                                <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition duration-300">
                                    Mettre à jour la sécurité
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Paramètres d'affichage -->
                <div>
                    <h2 class="text-xl font-semibold mb-4 text-gray-300">Préférences d'affichage</h2>
                    <div class="bg-gray-700 rounded-lg p-6 border border-gray-600">
                        <form action="{{ route('settings.display') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Thème -->
                                <div>
                                    <h3 class="font-semibold mb-4 text-gray-300">Thème</h3>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="theme" value="light" class="form-radio text-orange-500 bg-gray-800 border-gray-600">
                                            <span class="ml-2 text-gray-300">Clair</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="theme" value="dark" class="form-radio text-orange-500 bg-gray-800 border-gray-600" checked>
                                            <span class="ml-2 text-gray-300">Sombre</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="theme" value="system" class="form-radio text-orange-500 bg-gray-800 border-gray-600">
                                            <span class="ml-2 text-gray-300">Système</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Langue -->
                                <div>
                                    <h3 class="font-semibold mb-4 text-gray-300">Langue</h3>
                                    <select name="language" class="block w-full rounded-md bg-gray-800 border-gray-600 text-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200">
                                        <option value="fr">Français</option>
                                        <option value="en">English</option>
                                        <option value="es">Español</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-6">
                                <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition duration-300">
                                    Enregistrer les préférences
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour afficher les notifications
    function showNotification(type, message) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white z-50 transform transition-all duration-300 translate-y-0`;
        
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                <p>${message}</p>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animation d'entrée
        setTimeout(() => {
            notification.style.transform = 'translateY(10px)';
        }, 100);

        // Disparition automatique
        setTimeout(() => {
            notification.style.transform = 'translateY(-100%)';
            notification.style.opacity = '0';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Gérer la soumission des formulaires avec AJAX
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const formData = new FormData(this);
                const response = await fetch(this.action, {
                    method: this.method,
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                
                if (response.ok) {
                    showNotification('success', data.message || 'Modifications enregistrées avec succès');
                } else {
                    throw new Error(data.message || 'Une erreur est survenue');
                }
            } catch (error) {
                showNotification('error', error.message);
            }
        });
    });

    // Gérer l'activation/désactivation de 2FA
    const twoFactorToggle = document.querySelector('input[name="enable_2fa"]');
    if (twoFactorToggle) {
        twoFactorToggle.addEventListener('change', async function() {
            try {
                const response = await fetch('/parametres/2fa', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        enabled: this.checked
                    })
                });

                const data = await response.json();
                
                if (response.ok) {
                    showNotification('success', data.message);
                    if (data.qrCode) {
                        // Afficher une modal avec le QR code
                        const modal = document.createElement('div');
                        modal.className = 'fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50';
                        modal.innerHTML = `
                            <div class="bg-gray-800 p-6 rounded-lg shadow-xl max-w-md w-full">
                                <h3 class="text-xl font-bold mb-4 text-gray-300">Configuration de l'authentification à deux facteurs</h3>
                                <div class="mb-4">
                                    <p class="text-gray-400 mb-4">Scannez ce QR code avec votre application d'authentification :</p>
                                    <img src="${data.qrCode}" alt="QR Code" class="mx-auto">
                                </div>
                                <button class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 w-full">Fermer</button>
                            </div>
                        `;
                        
                        document.body.appendChild(modal);
                        
                        modal.querySelector('button').addEventListener('click', () => {
                            document.body.removeChild(modal);
                        });
                    }
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                showNotification('error', error.message);
                this.checked = !this.checked; // Rétablir l'état précédent
            }
        });
    }
});
</script>
@endpush
@endsection
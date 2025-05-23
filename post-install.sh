#!/bin/bash

# Créer les répertoires nécessaires
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/app/public
mkdir -p bootstrap/cache

# Définir les permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Créer le lien symbolique pour le stockage
php artisan storage:link

# Optimiser l'application
php artisan config:cache
php artisan route:cache
php artisan view:cache

#!/bin/bash

# Optimiser l'application
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link

# Permissions
chmod -R 775 storage bootstrap/cache
chmod -R 775 public/storage

# Migrations
php artisan migrate --force

# Clear caches that might interfere with production
php artisan cache:clear
php artisan config:clear

#!/bin/bash

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Clear and cache config
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Install Node dependencies
npm install

# Build assets
npm run build

#!/bin/bash
set -e

# Ensure we're in the project directory
cd "${0%/*}"

# Add Composer's vendor bin to PATH
export PATH="$PATH:vendor/bin"

echo "🔍 Checking PHP version..."
which php
php -v

echo "📦 Installing PHP dependencies..."
COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader --no-interaction

echo "🧹 Clearing Laravel cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "🔑 Checking application key..."
php artisan key:generate --force

echo "📦 Installing Node.js dependencies..."
npm ci --prefer-offline --no-audit

echo "🏗️ Building assets..."
npm run build

echo "⚡ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Build completed successfully!"

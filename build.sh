#!/bin/bash
set -e

echo "📂 Setting up project..."
cd "${BASH_SOURCE%/*}" || exit

# Configuration PHP
echo "🔧 PHP Configuration..."
export PATH="/usr/local/bin:$PATH"
export PATH="$HOME/.php/bin:$PATH"
export PATH="$HOME/.composer/vendor/bin:$PATH"

if ! command -v php &> /dev/null; then
    echo "❌ PHP not found. Using alternative path..."
    export PATH="/opt/php/bin:$PATH"
fi

echo "🔍 PHP Location and Version:"
which php || echo "PHP not found in PATH"
php -v || echo "Cannot get PHP version"

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

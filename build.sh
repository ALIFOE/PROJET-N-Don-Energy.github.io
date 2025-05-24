#!/bin/bash
set -e

echo "📂 Setting up project..."
cd "${BASH_SOURCE%/*}" || exit

echo "🔍 Checking system..."
php --version
composer --version
node --version
npm --version

echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "🔧 Setting up Laravel..."
php artisan key:generate --force
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "📦 Installing NPM dependencies..."
npm ci

echo "🏗️ Building assets..."
npm run build

echo "⚡ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Build completed successfully!"

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

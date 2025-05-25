#!/bin/bash
set -e

echo "📂 Setting up project..."
cd "${BASH_SOURCE%/*}" || exit

# Installation de PHP et ses dépendances
echo "🔧 Installing PHP and dependencies..."
apt-get update || { echo "Failed to update package list"; exit 1; }
apt-get install -y php8.0-cli php8.0-common php8.0-mysql php8.0-zip php8.0-gd php8.0-mbstring php8.0-curl php8.0-xml php8.0-bcmath || {
    echo "Failed to install PHP. Trying alternative method..."
    curl -o php-setup.sh https://packages.sury.org/php/apt.gpg
    apt-key add php-setup.sh
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list
    apt-get update
    apt-get install -y php8.0-cli php8.0-common php8.0-mysql php8.0-zip php8.0-gd php8.0-mbstring php8.0-curl php8.0-xml php8.0-bcmath
}

# Installation de Composer si nécessaire
if ! [ -x "$(command -v composer)" ]; then
    echo "📥 Installing Composer..."
    EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')"
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

    if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]; then
        >&2 echo 'ERROR: Invalid installer checksum'
        rm composer-setup.php
        exit 1
    fi

    php composer-setup.php --install-dir=/usr/local/bin --filename=composer || {
        echo "Failed to install Composer in /usr/local/bin, trying alternate location..."
        mkdir -p "$HOME/bin"
        php composer-setup.php --install-dir="$HOME/bin" --filename=composer
        export PATH="$HOME/bin:$PATH"
    }
    rm composer-setup.php
fi

# Vérifier que composer est dans le PATH
export PATH="/usr/local/bin:$HOME/bin:$PATH"

echo "🔍 Checking system..."
php -v || echo "PHP not installed"
composer -V || echo "Composer not installed"
node -v || echo "Node not installed"
npm -v || echo "NPM not installed"

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

# Mise en place des permissions
mkdir -p bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Installation des dépendances
composer install --no-dev --optimize-autoloader
npm install

# Compilation des assets
npm run build

# Configuration de Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

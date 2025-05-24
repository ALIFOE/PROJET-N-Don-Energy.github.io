FROM php:8.0-apache

# Création des répertoires nécessaires
RUN mkdir -p /var/lib/apt/lists/partial

# Installation des dépendances système avec gestion des erreurs
RUN set -e; \
    mkdir -p /var/lib/apt/lists/partial && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Installation de Node.js et npm
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - && \
    apt-get install -y nodejs

# Installation des extensions PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Configuration d'Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

# Copie des fichiers du projet
WORKDIR /var/www/html
COPY . /var/www/html

# Installation des dépendances avec des permissions appropriées
RUN set -e; \
    composer install --no-dev --optimize-autoloader --no-interaction && \
    npm install && npm run build && \
    chown -R www-data:www-data /var/www/html

# Permissions avec gestion des erreurs
RUN set -e; \
    chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Optimisations Laravel
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

EXPOSE 80

CMD ["apache2-foreground"]

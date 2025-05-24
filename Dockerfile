FROM php:8.0-apache

# Configuration du système pour éviter les problèmes de permissions
RUN set -eux; \
    apt-get update || ( rm -rf /var/lib/apt/lists/* && mkdir -p /var/lib/apt/lists/partial && apt-get clean && apt-get update ) && \
    apt-get install -y \
        git \
        curl \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        zip \
        unzip \
    && rm -rf /var/lib/apt/lists/*

# Installation de Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Installation des extensions PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Configuration d'Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    a2enmod rewrite

# Préparation du répertoire de travail
WORKDIR /var/www/html

# Copie des fichiers composer pour installation des dépendances
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader

# Copie des fichiers npm
COPY package.json package-lock.json ./
RUN npm install

# Copie du reste des fichiers
COPY . .

# Finalisation de l'installation
RUN composer dump-autoload --optimize && \
    npm run build && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Configuration des permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]

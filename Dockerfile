# Utiliser une image Alpine pour éviter les problèmes de système de fichiers
FROM php:8.0-fpm-alpine3.14

# Installation des dépendances essentielles
RUN apk add --no-cache \
    apache2 \
    apache2-proxy \
    apache2-ssl \
    nodejs \
    npm \
    git \
    libpng-dev \
    zip \
    unzip \
    && rm -rf /var/cache/apk/*

# Installation des extensions PHP
RUN docker-php-ext-install pdo_mysql bcmath

# Installation de Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Configuration
WORKDIR /var/www/html

# Copie des fichiers de configuration
COPY docker/apache.conf /etc/apache2/conf.d/custom.conf
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

# Configuration de l'environnement
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_NO_INTERACTION=1
ENV COMPOSER_MEMORY_LIMIT=-1

# Copie des fichiers de dépendances
COPY composer.* ./
COPY package*.json ./

# Installation des dépendances
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist
RUN npm ci --production --no-audit

# Copie de l'application
COPY . .

# Finalisation
RUN composer dump-autoload --optimize --classmap-authoritative && \
    npm run build && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    chown -R apache:apache storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache && \
    mkdir -p /run/apache2

EXPOSE 80

CMD ["/start.sh"]

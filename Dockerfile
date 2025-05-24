# Utiliser l'image officielle PHP avec Apache
FROM php:8.0-apache

# Activation des modules Apache nécessaires
RUN a2enmod rewrite

# Installation des extensions PHP nécessaires
RUN docker-php-ext-install pdo_mysql bcmath

# Installation de Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - && \
    apt-get install -y nodejs

# Installation de Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Configuration d'Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copie de la configuration Apache personnalisée
COPY apache2-render-config.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# Configuration de l'environnement
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_NO_INTERACTION=1
ENV COMPOSER_MEMORY_LIMIT=-1
ENV NODE_ENV=production

# Copie des fichiers de dépendances
COPY composer.* ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY package*.json ./
RUN npm ci --production --no-audit

# Copie du reste de l'application
COPY . .

# Finalisation de l'installation
RUN composer dump-autoload --optimize --classmap-authoritative && \
    npm run build && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]

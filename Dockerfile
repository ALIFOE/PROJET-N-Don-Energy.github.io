# Utiliser une image de base plus légère
FROM php:8.0-apache-slim-bullseye

# Installation des extensions PHP nécessaires
RUN docker-php-ext-install pdo_mysql bcmath

# Installation des dépendances système requises sans utiliser apt
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer
COPY --from=node:16-bullseye-slim /usr/local/bin/node /usr/local/bin/node
COPY --from=node:16-bullseye-slim /usr/local/lib/node_modules /usr/local/lib/node_modules

# Configuration de npm
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

# Configuration d'Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    a2enmod rewrite

# Préparation du répertoire de travail
WORKDIR /var/www/html

# Configuration de Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_NO_INTERACTION=1
ENV COMPOSER_MEMORY_LIMIT=-1

# Copie des fichiers de configuration
COPY composer.* ./
COPY package*.json ./

# Installation des dépendances
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist
RUN npm ci --production

# Copie du reste des fichiers
COPY . .

# Finalisation de l'installation
RUN set -e; \
    composer dump-autoload --optimize --classmap-authoritative; \
    npm run build; \
    php artisan config:cache; \
    php artisan route:cache; \
    php artisan view:cache; \
    chown -R www-data:www-data storage bootstrap/cache; \
    chmod -R 775 storage bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]

FROM php:8.0-apache-bullseye

# Copie de composer depuis l'image officielle
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Installation de Node.js depuis l'image officielle
COPY --from=node:16-bullseye-slim /usr/local/bin/node /usr/local/bin/node
COPY --from=node:16-bullseye-slim /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

# Installation des extensions PHP requises
RUN docker-php-ext-install pdo_mysql bcmath

# Configuration d'Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    a2enmod rewrite

# Création des répertoires nécessaires
WORKDIR /var/www/html

# Copie des fichiers de dépendances
COPY composer.json composer.lock ./
COPY package.json package-lock.json ./

# Installation des dépendances en mode production
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist
RUN npm ci --production

# Copie du reste des fichiers
COPY . .

# Finalisation de l'installation
RUN composer dump-autoload --optimize --classmap-authoritative && \
    npm run build && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Configuration des permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]

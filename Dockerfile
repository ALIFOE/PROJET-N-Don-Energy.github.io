# Stage 1: Dependencies
FROM composer:2.5 as vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Stage 2: Node modules
FROM node:16-alpine as node
WORKDIR /app
COPY package*.json ./
RUN npm ci --production --no-audit
COPY . .
RUN npm run build

# Stage 3: Final image
FROM php:8.0-apache-buster

# Copie des fichiers nécessaires du builder
COPY --from=vendor /app/vendor /var/www/html/vendor
COPY --from=node /app/public/build /var/www/html/public/build
COPY --from=node /app/public/js /var/www/html/public/js
COPY --from=node /app/public/css /var/www/html/public/css

# Installation des extensions PHP uniquement
RUN docker-php-ext-install pdo_mysql bcmath

# Configuration d'Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Activation du module rewrite
RUN a2enmod rewrite

# Configuration du DocumentRoot
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copie de l'application
COPY . /var/www/html/

WORKDIR /var/www/html

# Configuration des permissions sans utiliser chown
RUN mkdir -p storage/framework/{sessions,views,cache} && \
    mkdir -p bootstrap/cache && \
    chmod -R 777 storage bootstrap/cache && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

EXPOSE 80

CMD ["apache2-foreground"]

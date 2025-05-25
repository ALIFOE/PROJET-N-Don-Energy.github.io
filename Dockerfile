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
FROM bitnami/php-fpm:8.0 AS builder

# Pas besoin d'apt-get, l'image Bitnami inclut déjà les outils nécessaires
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer
COPY --from=node:16-alpine /usr/local/bin/node /usr/local/bin/node
COPY --from=node:16-alpine /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

WORKDIR /app

# Copie et installation des dépendances
COPY composer.* ./
RUN composer install --no-dev --no-scripts --prefer-dist

COPY package*.json ./
RUN npm ci --production

# Copie du reste de l'application et build
COPY . .
RUN npm run build

# Image finale
FROM bitnami/php-fpm:8.0

# Configuration Apache
ENV APACHE_HTTP_PORT_NUMBER=80
ENV APACHE_DOCUMENT_ROOT=/app/public

# Copie des fichiers depuis le builder
COPY --from=builder /app /app
WORKDIR /app

# Configuration finale
RUN chmod -R 777 storage bootstrap/cache && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

EXPOSE 80

CMD ["php-fpm"]

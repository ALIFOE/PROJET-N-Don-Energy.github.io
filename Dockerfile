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
FROM php:8.0-apache
COPY --from=vendor /app/vendor /var/www/html/vendor
COPY --from=node /app/public/build /var/www/html/public/build
COPY --from=node /app/public/js /var/www/html/public/js
COPY --from=node /app/public/css /var/www/html/public/css

# PHP extensions
RUN docker-php-ext-install pdo_mysql bcmath

# Apache configuration
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN a2enmod rewrite && \
    sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy application
COPY . /var/www/html/
COPY apache2-render-config.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# Optimize and cache
RUN composer dump-autoload --optimize --classmap-authoritative && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]

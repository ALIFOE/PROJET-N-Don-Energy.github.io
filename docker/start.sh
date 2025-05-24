#!/bin/sh

# Démarrage de PHP-FPM en arrière-plan
php-fpm -D

# Vérification des permissions
chown -R apache:apache /var/www/html/storage
chown -R apache:apache /var/www/html/bootstrap/cache

# Démarrage d'Apache en premier plan
exec httpd -DFOREGROUND

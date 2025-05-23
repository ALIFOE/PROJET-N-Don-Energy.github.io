#!/bin/bash

# Définir le chemin PHP
PHP_PATH="C:/xampp/php/php.exe"
if [ ! -f "$PHP_PATH" ]; then
    PHP_PATH="php"  # Utiliser php par défaut si xampp n'est pas trouvé
fi

# Exécuter les commandes artisan avec le chemin PHP approprié
"$PHP_PATH" artisan storage:link
"$PHP_PATH" artisan migrate --force
"$PHP_PATH" artisan cache:clear
"$PHP_PATH" artisan config:clear
"$PHP_PATH" artisan serve --host=0.0.0.0 --port=$PORT

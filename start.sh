#!/bin/bash
php artisan storage:link
php artisan migrate --force
php artisan cache:clear
php artisan config:clear
php artisan serve --host=0.0.0.0 --port=$PORT

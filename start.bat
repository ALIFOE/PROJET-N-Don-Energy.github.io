@echo off
SET PHP_PATH=C:\xampp\php\php.exe

REM Vérifier si PHP existe dans le chemin spécifié
IF NOT EXIST "%PHP_PATH%" (
    echo PHP non trouvé dans %PHP_PATH%, utilisation de la commande php par défaut
    SET PHP_PATH=php
)

echo Création du lien symbolique pour le stockage...
%PHP_PATH% artisan storage:link

echo Exécution des migrations...
%PHP_PATH% artisan migrate --force

echo Nettoyage du cache...
%PHP_PATH% artisan cache:clear
%PHP_PATH% artisan config:clear

echo Démarrage du serveur...
%PHP_PATH% artisan serve --host=0.0.0.0 --port=8000

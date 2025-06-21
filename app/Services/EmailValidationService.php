<?php

namespace App\Services;

class EmailValidationService
{
    protected $allowedDomains = [
        'gmail.com',
        'hotmail.com',
        'outlook.com',
        'outlook.fr',
        'yahoo.com',
        'yahoo.fr',
        'live.com',
        'live.fr',
        'msn.com'
    ];

    public function isAllowedDomain(string $email): bool
    {
        $domain = strtolower(substr(strrchr($email, "@"), 1));
        return in_array($domain, $this->allowedDomains);
    }

    public function verifyEmailExists(string $email): bool
    {
        $domain = substr(strrchr($email, "@"), 1);
        $mxhosts = [];

        // Vérifie les enregistrements MX
        if (!getmxrr($domain, $mxhosts)) {
            return false;
        }

        // Vérifie si le serveur SMTP répond
        $connection = @fsockopen($mxhosts[0], 25, $errno, $errstr, 5);
        if (!$connection) {
            return false;
        }

        $response = fgets($connection);
        if (substr($response, 0, 3) !== '220') {
            fclose($connection);
            return false;
        }

        // Simule une session SMTP
        fputs($connection, "HELO example.com\r\n");
        fgets($connection);
        fputs($connection, "MAIL FROM: <verify@example.com>\r\n");
        fgets($connection);
        fputs($connection, "RCPT TO: <{$email}>\r\n");
        $response = fgets($connection);
        fputs($connection, "QUIT\r\n");
        fclose($connection);

        // Si le code de réponse commence par 250, l'adresse existe probablement
        return substr($response, 0, 3) === '250';
    }
}

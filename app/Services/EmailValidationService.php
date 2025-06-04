<?php

namespace App\Services;

class EmailValidationService
{
    /**
     * Liste des domaines autorisés
     */
    private $allowedDomains = [
        'gmail.com',
        'outlook.com',
        'hotmail.com',
        'live.com',
        'yahoo.com',
        'yahoo.fr'
    ];

    /**
     * Vérifie si le domaine de l'email est autorisé
     */
    public function isAllowedDomain(string $email): bool
    {
        $domain = strtolower(explode('@', $email)[1] ?? '');
        return in_array($domain, $this->allowedDomains);
    }

    /**
     * Vérifie si l'email existe réellement
     * Note: Cette méthode utilise une vérification DNS simple
     */
    public function verifyEmailExists(string $email): bool
    {
        $domain = explode('@', $email)[1] ?? '';
        
        // Vérifie si le domaine a des enregistrements MX valides
        return checkdnsrr($domain, 'MX');
    }
}

<?php

namespace App\Services;

class DevisAnalyzer
{
    private $calculateurSolaire;

    public function __construct(CalculateurSolaire $calculateurSolaire)
    {
        $this->calculateurSolaire = $calculateurSolaire;
    }

    public function analyserDevis(array $donnees): array
    {
        // Étape 1 : Évaluation de la faisabilité
        $faisabilite = $this->calculateurSolaire->evaluerFaisabilite($donnees);
        
        if (!$faisabilite['faisable']) {
            return [
                'status' => 'non_faisable',
                'message' => 'Le projet n\'est pas techniquement réalisable : ' . $faisabilite['commentaires'],
                'faisabilite' => $faisabilite
            ];
        }

        // Étape 2 : Dimensionnement
        $dimensionnement = $this->calculateurSolaire->dimensionnerInstallation($donnees);

        // Étape 3 : Analyse financière
        $analyseFin = $this->calculateurSolaire->calculerRetourInvestissement($donnees + ['dimensionnement' => $dimensionnement], $dimensionnement['puissance_kwc']);

        // Étape 4 : Générer les recommandations
        $recommandations = $this->genererRecommandations($donnees, $dimensionnement, $analyseFin);

        return [
            'status' => 'success',
            'faisabilite' => $faisabilite,
            'dimensionnement' => $dimensionnement,
            'analyse_financiere' => $analyseFin,
            'recommandations' => $recommandations
        ];
    }

    private function genererRecommandations(array $donnees, array $dimensionnement, array $analyseFin): array
    {
        $recommandations = [];

        // Recommandations basées sur l'orientation
        if ($donnees['orientation'] !== 'sud') {
            $recommandations[] = 'Envisager des optimiseurs de puissance pour compenser l\'orientation non optimale';
        }

        // Recommandations basées sur la consommation
        if ($dimensionnement['puissance_kwc'] > 9) {
            $recommandations[] = 'Installation importante : un système de monitoring avancé est recommandé';
        }

        // Recommandations financières
        if ($analyseFin['retour_investissement_annees'] > 10) {
            $recommandations[] = 'Envisager des solutions d\'optimisation pour réduire le temps de retour sur investissement';
        }

        // Recommandations sur le stockage
        if (in_array('autonomie', $donnees['objectifs'] ?? [])) {
            $recommandations[] = 'Un système de stockage par batteries est recommandé pour atteindre vos objectifs d\'autonomie';
        }

        return $recommandations;
    }
}
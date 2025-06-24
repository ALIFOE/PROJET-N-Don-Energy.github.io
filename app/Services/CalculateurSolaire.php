<?php

namespace App\Services;

use App\Models\Panneau;

class CalculateurSolaire
{    private const ENSOLEILLEMENT_MOYEN = 1200; // kWh/m²/an (moyenne française)
    private const PRIX_MOYEN_KWC = 2000; // Prix moyen par kWc en euros
    private const PRIX_KWH = 0.1740; // Prix moyen du kWh en France

    private function getPanneauOptimal(): Panneau
    {
        $panneau = Panneau::where('type', 'Monocristallin haute performance')->first();
        
        if (!$panneau) {
            // Si aucun panneau monocristallin haute performance n'est trouvé, on prend le premier panneau disponible
            $panneau = Panneau::first();
            
            if (!$panneau) {
                // Si toujours aucun panneau n'est trouvé, on crée un panneau par défaut
                $panneau = new Panneau();
                $panneau->type = 'Monocristallin haute performance';
                $panneau->capacite_wc = 400; // Valeur typique pour un panneau moderne
                $panneau->surface = 1.7; // Surface moyenne en m²
                $panneau->rendement = 0.21; // 21% de rendement
                $panneau->fabricant = 'Standard';
                $panneau->modele = 'Modèle standard';
                $panneau->garantie_annees = 25;
                $panneau->save();
            }
        }
        
        return $panneau;
    }

    public function evaluerFaisabilite(array $donnees): array
    {
        $orientation = $donnees['orientation'] ?? 'sud';
        $type_toiture = $donnees['type_toiture'] ?? 'tuiles';
        
        $coefficientOrientation = $this->getCoeffientOrientation($orientation);
        $faisabiliteToiture = $this->evaluerFaisabiliteToiture($type_toiture);
        
        return [
            'faisable' => $faisabiliteToiture['faisable'],
            'score_faisabilite' => $coefficientOrientation * ($faisabiliteToiture['score'] / 100),
            'commentaires' => $faisabiliteToiture['commentaires'],
            'coefficient_rendement' => $coefficientOrientation
        ];
    }

    public function dimensionnerInstallation(array $donnees): array
    {
        $consommationAnnuelle = $donnees['consommation_annuelle'];
        $objectifs = $donnees['objectifs'] ?? ['autoconsommation'];
        
        // Récupération du panneau optimal
        $panneau = $this->getPanneauOptimal();
        
        // Calcul de la puissance nécessaire
        $puissanceNecessaire = $this->calculerPuissanceNecessaire($consommationAnnuelle, $objectifs);
        
        // Nombre de panneaux nécessaires (en kWc)
        $nombrePanneaux = max(1, min(20, floor($puissanceNecessaire / ($panneau->capacite_wc / 1000))));
        // Surface nécessaire
        $surfaceNecessaire = $nombrePanneaux * $panneau->surface;

        // Calcul du nombre de batteries (exemple : 1 batterie 12V/200Ah ≈ 2,4 kWh)
        $energieStockage = $puissanceNecessaire * 1000 * 1; // 1h d'autonomie, à adapter selon besoin
        $capaciteBatterieKwh = 2.4; // 12V * 200Ah * 1/1000
        $nombreBatteries = max(1, ceil($energieStockage / ($capaciteBatterieKwh * 1000)));

        return [
            'puissance_kwc' => $puissanceNecessaire,
            'nombre_panneaux' => $nombrePanneaux,
            'surface_necessaire' => $surfaceNecessaire,
            'production_estimee' => $this->calculerProductionAnnuelle($puissanceNecessaire, $donnees['orientation']),
            'type_panneau' => $panneau->type,
            'capacite_panneau' => $panneau->capacite_wc,
            'rendement_panneau' => $panneau->rendement,
            'fabricant' => $panneau->fabricant,
            'modele' => $panneau->modele,
            'garantie_annees' => $panneau->garantie_annees,
            'nombre_batteries' => $nombreBatteries
        ];
    }

    public function calculerRetourInvestissement(array $donnees, float $puissanceKwc): array
    {
        $coutInstallation = $this->calculerCoutInstallation($puissanceKwc);
        $economiesAnnuelles = $this->calculerEconomiesAnnuelles($donnees);
        $retourInvestissement = $coutInstallation / $economiesAnnuelles;
        
        return [
            'cout_installation' => $coutInstallation,
            'economies_annuelles' => $economiesAnnuelles,
            'retour_investissement_annees' => round($retourInvestissement, 1),
            'rentabilite_20_ans' => $economiesAnnuelles * 20 - $coutInstallation
        ];
    }

    private function getCoeffientOrientation(string $orientation): float
    {
        return match($orientation) {
            'sud' => 1.0,
            'sud-est', 'sud-ouest' => 0.95,
            'est', 'ouest' => 0.85,
            default => 0.75,
        };
    }

    private function evaluerFaisabiliteToiture(string $type): array
    {
        return match($type) {
            'tuiles' => [
                'faisable' => true,
                'score' => 90,
                'commentaires' => 'Installation standard sur tuiles, bonne faisabilité'
            ],
            'ardoises' => [
                'faisable' => true,
                'score' => 85,
                'commentaires' => 'Installation possible sur ardoises, attention particulière nécessaire'
            ],
            'toit_plat' => [
                'faisable' => true,
                'score' => 95,
                'commentaires' => 'Excellente faisabilité sur toit plat, installation optimisable'
            ],
            'metal' => [
                'faisable' => true,
                'score' => 100,
                'commentaires' => 'Installation idéale sur toiture métallique'
            ],
            default => [
                'faisable' => false,
                'score' => 0,
                'commentaires' => 'Type de toiture non supporté'
            ],
        };
    }

    private function calculerPuissanceNecessaire(float $consommationAnnuelle, array $objectifs): float
    {
        $coefficient = in_array('autonomie', $objectifs) ? 1.2 : 1.0;
        $panneau = $this->getPanneauOptimal();
        return round(($consommationAnnuelle * $coefficient) / (self::ENSOLEILLEMENT_MOYEN * $panneau->rendement), 2);
    }

    private function calculerProductionAnnuelle(float $puissanceKwc, string $orientation): float
    {
        $coefficientOrientation = $this->getCoeffientOrientation($orientation);
        $panneau = $this->getPanneauOptimal();
        return round($puissanceKwc * self::ENSOLEILLEMENT_MOYEN * $panneau->rendement * $coefficientOrientation, 2);
    }

    private function calculerCoutInstallation(float $puissanceKwc): float
    {
        return round($puissanceKwc * self::PRIX_MOYEN_KWC, 2);
    }

    private function calculerEconomiesAnnuelles(array $donnees): float
    {
        $production = $this->calculerProductionAnnuelle(
            $donnees['dimensionnement']['puissance_kwc'],
            $donnees['orientation']
        );
        
        return round($production * self::PRIX_KWH, 2);
    }
}
<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Panneaux Solaires
            [
                'nom' => 'Panneau Solaire 400W Monocristallin',
                'description' => 'Haute efficacité, idéal pour les installations résidentielles et commerciales.',
                'prix' => 150000,
                'categorie' => 'panels',
                'image' => 'images/panneaux.jpg',
                'en_stock' => true,
            ],
            [
                'nom' => 'Panneau Solaire 550W Bifacial',
                'description' => 'Technologie bifaciale pour une production optimale des deux côtés.',
                'prix' => 200000,
                'categorie' => 'panels',
                'image' => 'images/panneau 2.jpg'
            ],
            [
                'nom' => 'Panneau Solaire 300W Polycristallin',
                'description' => 'Solution économique pour les petites installations.',
                'prix' => 120000,
                'categorie' => 'panels',
                'image' => 'images/panneau-poli.jpg'
            ],
            [
                'nom' => 'Panneau Solaire 600W Monocristallin',
                'description' => 'Haute puissance pour les installations industrielles.',
                'prix' => 250000,
                'categorie' => 'panels',
                'image' => 'images/paneau-mono.jpg'
            ],
            [
                'nom' => 'Panneau Solaire Flexible 200W',
                'description' => 'Idéal pour les surfaces courbes et les applications mobiles.',
                'prix' => 100000,
                'categorie' => 'panels',
                'image' => 'images/panneau-flexible.jpg'
            ],

            // Onduleurs
            [
                'nom' => 'Onduleur Hybride 5kW',
                'description' => 'Compatible avec batterie et réseau, idéal pour les maisons moyennes.',
                'prix' => 300000,
                'categorie' => 'inverters',
                'image' => 'images/onduleur1.jpg',
                'en_stock' => true,
            ],
            [
                'nom' => 'Onduleur 3kW Standard',
                'description' => 'Solution économique pour les petites installations.',
                'prix' => 200000,
                'categorie' => 'inverters',
                'image' => 'images/onduleur2.jpg'
            ],
            [
                'nom' => 'Onduleur 10kW Industriel',
                'description' => 'Puissance élevée pour les applications commerciales et industrielles.',
                'prix' => 500000,
                'categorie' => 'inverters',
                'image' => 'images/products/onduleur-10kw.jpg'
            ],
            [
                'nom' => 'Micro-onduleur 800W',
                'description' => 'Optimisation individuelle pour chaque panneau.',
                'prix' => 150000,
                'categorie' => 'inverters',
                'image' => 'images/products/onduleur-micro.jpg'
            ],

            // Batteries
            [
                'nom' => 'Batterie Lithium 5kWh',
                'description' => 'Haute densité énergétique, longue durée de vie.',
                'prix' => 400000,
                'categorie' => 'batteries',
                'image' => 'images/products/batterie-lithium.jpg',
                'en_stock' => true,
            ],
            [
                'nom' => 'Batterie Gel 200Ah',
                'description' => 'Sans entretien, idéale pour les installations solaires.',
                'prix' => 250000,
                'categorie' => 'batteries',
                'image' => 'images/products/batterie-gel.jpg'
            ],
            [
                'nom' => 'Batterie AGM 100Ah',
                'description' => 'Haute performance, maintenance réduite.',
                'prix' => 180000,
                'categorie' => 'batteries',
                'image' => 'images/products/batterie-agm.jpg'
            ],
            [
                'nom' => 'Batterie Lithium 10kWh',
                'description' => 'Capacité élevée pour les installations importantes.',
                'prix' => 800000,
                'categorie' => 'batteries',
                'image' => 'images/products/batterie-lithium-10kwh.jpg'
            ],

            // Régulateurs
            [
                'nom' => 'Régulateur MPPT 60A',
                'description' => 'Suivi du point de puissance maximum, haute efficacité.',
                'prix' => 80000,
                'categorie' => 'regulators',
                'image' => 'images/products/regulateur-mppt.jpg',
                'en_stock' => true,
            ],
            [
                'nom' => 'Régulateur PWM 30A',
                'description' => 'Solution économique pour les petites installations.',
                'prix' => 50000,
                'categorie' => 'regulators',
                'image' => 'images/products/regulateur-pwm.jpg'
            ],
            [
                'nom' => 'Régulateur MPPT 100A',
                'description' => 'Haute puissance pour les grandes installations.',
                'prix' => 150000,
                'categorie' => 'regulators',
                'image' => 'images/products/regulateur-mppt-100a.jpg'
            ],
            [
                'nom' => 'Régulateur MPPT 80A',
                'description' => 'Performance intermédiaire pour les installations moyennes.',
                'prix' => 120000,
                'categorie' => 'regulators',
                'image' => 'images/products/regulateur-mppt-80a.jpg'
            ],

            // Accessoires
            [
                'nom' => 'Kit de Montage Toiture',
                'description' => 'Système complet pour installation sur toit incliné.',
                'prix' => 50000,
                'categorie' => 'accessories',
                'image' => 'images/products/kit-montage.jpg',
                'en_stock' => true,
            ],
            [
                'nom' => 'Câbles Solaires 6mm²',
                'description' => 'Rouleau de 100m, résistant aux UV.',
                'prix' => 30000,
                'categorie' => 'accessories',
                'image' => 'images/products/cables-solaires.jpg'
            ],
            [
                'nom' => 'Boîte de Protection DC',
                'description' => 'Protection contre les surtensions et courts-circuits.',
                'prix' => 25000,
                'categorie' => 'accessories',
                'image' => 'images/products/boite-protection.jpg'
            ],
            [
                'nom' => 'Compteur d\'Énergie Bidirectionnel',
                'description' => 'Mesure la production et la consommation d\'énergie.',
                'prix' => 40000,
                'categorie' => 'accessories',
                'image' => 'images/products/compteur-energie.jpg'
            ],
            [
                'nom' => 'Panneau Solaire 300W',
                'description' => 'Panneau solaire monocristallin haute performance de 300W',
                'prix' => 150000,
                'categorie' => 'panels',
                'en_stock' => true,
            ],
            [
                'nom' => 'Onduleur 2000W',
                'description' => 'Onduleur pur sinus de 2000W pour système solaire',
                'prix' => 120000,
                'categorie' => 'inverters',
                'en_stock' => true,
            ],
            [
                'nom' => 'Batterie Gel 100Ah',
                'description' => 'Batterie Gel 12V 100Ah pour système solaire',
                'prix' => 90000,
                'categorie' => 'batteries',
                'en_stock' => true,
            ],
            [
                'nom' => 'Régulateur MPPT 40A',
                'description' => 'Régulateur de charge MPPT 40A pour panneaux solaires',
                'prix' => 45000,
                'categorie' => 'regulators',
                'en_stock' => true,
            ],
            [
                'nom' => 'Kit de Câbles Solaires',
                'description' => 'Kit complet de câbles pour installation solaire',
                'prix' => 25000,
                'categorie' => 'accessories',
                'en_stock' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
} 
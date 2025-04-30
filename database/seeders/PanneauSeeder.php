<?php

namespace Database\Seeders;

use App\Models\Panneau;
use Illuminate\Database\Seeder;

class PanneauSeeder extends Seeder
{
    public function run()
    {
        $panneaux = [
            [
                'type' => 'Monocristallin haute performance',
                'capacite_wc' => 375,
                'surface' => 1.7,
                'rendement' => 0.20,
                'fabricant' => 'SunPower',
                'modele' => 'MAX3-375',
                'garantie_annees' => 25
            ],
            [
                'type' => 'Monocristallin standard',
                'capacite_wc' => 330,
                'surface' => 1.6,
                'rendement' => 0.18,
                'fabricant' => 'JinkoSolar',
                'modele' => 'Tiger Pro',
                'garantie_annees' => 20
            ],
            [
                'type' => 'Polycristallin',
                'capacite_wc' => 280,
                'surface' => 1.6,
                'rendement' => 0.16,
                'fabricant' => 'Canadian Solar',
                'modele' => 'KuPower',
                'garantie_annees' => 15
            ]
        ];

        foreach ($panneaux as $panneau) {
            Panneau::create($panneau);
        }
    }
}
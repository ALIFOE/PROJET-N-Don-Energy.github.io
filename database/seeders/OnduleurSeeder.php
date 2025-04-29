<?php

namespace Database\Seeders;

use App\Models\Onduleur;
use Illuminate\Database\Seeder;

class OnduleurSeeder extends Seeder
{
    public function run(): void
    {
        $onduleurs = [
            // SMA
            [
                'marque' => 'SMA',
                'modele' => 'Sunny Boy 3.0',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'SMA30-001',
            ],
            [
                'marque' => 'SMA',
                'modele' => 'Sunny Boy 5.0',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'SMA50-001',
            ],
            [
                'marque' => 'SMA',
                'modele' => 'Sunny Boy 6.0',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'SMA60-001',
            ],
            [
                'marque' => 'SMA',
                'modele' => 'Sunny Tripower 8.0',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'SMATP80-001',
            ],
            [
                'marque' => 'SMA',
                'modele' => 'Sunny Tripower 10.0',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'SMATP100-001',
            ],
            
            // Huawei
            [
                'marque' => 'Huawei',
                'modele' => 'SUN2000-3KTL-M0',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'HW3K-001',
            ],
            [
                'marque' => 'Huawei',
                'modele' => 'SUN2000-5KTL-M0',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'HW5K-001',
            ],
            [
                'marque' => 'Huawei',
                'modele' => 'SUN2000-8KTL-M0',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'HW8K-001',
            ],
            [
                'marque' => 'Huawei',
                'modele' => 'SUN2000-10KTL-M0',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'HW10K-001',
            ],

            // Fronius
            [
                'marque' => 'Fronius',
                'modele' => 'Primo 3.0-1',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'FR30-001',
            ],
            [
                'marque' => 'Fronius',
                'modele' => 'Primo 5.0-1',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'FR50-001',
            ],
            [
                'marque' => 'Fronius',
                'modele' => 'Symo 8.2-3-M',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'FR82-001',
            ],
            [
                'marque' => 'Fronius',
                'modele' => 'Symo 10.0-3-M',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'FR100-001',
            ],

            // SolarEdge
            [
                'marque' => 'SolarEdge',
                'modele' => 'SE3K',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'SE3K-001',
            ],
            [
                'marque' => 'SolarEdge',
                'modele' => 'SE5K',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'SE5K-001',
            ],
            [
                'marque' => 'SolarEdge',
                'modele' => 'SE8K',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'SE8K-001',
            ],
            [
                'marque' => 'SolarEdge',
                'modele' => 'SE10K',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'SE10K-001',
            ],

            // Enphase
            [
                'marque' => 'Enphase',
                'modele' => 'IQ7',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'EN7-001',
            ],
            [
                'marque' => 'Enphase',
                'modele' => 'IQ7+',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'EN7P-001',
            ],
            [
                'marque' => 'Enphase',
                'modele' => 'IQ8',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'EN8-001',
            ],

            // Growatt
            [
                'marque' => 'Growatt',
                'modele' => 'MIN 3000TL-X',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'GW30-001',
            ],
            [
                'marque' => 'Growatt',
                'modele' => 'MIN 5000TL-X',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'GW50-001',
            ],
            [
                'marque' => 'Growatt',
                'modele' => 'MOD 8000TL3-X',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'GW80-001',
            ],

            // Sungrow
            [
                'marque' => 'Sungrow',
                'modele' => 'SG3.0RT',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'SG30-001',
            ],
            [
                'marque' => 'Sungrow',
                'modele' => 'SG5.0RT',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'SG50-001',
            ],
            [
                'marque' => 'Sungrow',
                'modele' => 'SG8.0RT',
                'connectable' => true,
                'user_id' => 1,
                'numero_serie' => 'SG80-001',
            ],

            // Modèles non connectables
            [
                'marque' => 'Generic Solar',
                'modele' => 'Basic 3000',
                'connectable' => false,
                'user_id' => 1,
                'numero_serie' => 'GS30-001',
            ],
            [
                'marque' => 'Generic Solar',
                'modele' => 'Basic 5000',
                'connectable' => false,
                'user_id' => 1,
                'numero_serie' => 'GS50-001',
            ],
            [
                'marque' => 'SolarBasic',
                'modele' => 'SB3000',
                'connectable' => false,
                'user_id' => 1,
                'numero_serie' => 'SB30-001',
            ],
            [
                'marque' => 'SolarBasic',
                'modele' => 'SB5000',
                'connectable' => false,
                'user_id' => 1,
                'numero_serie' => 'SB50-001',
            ]
        ];

        foreach ($onduleurs as $onduleur) {
            Onduleur::create($onduleur);
        }
    }
}

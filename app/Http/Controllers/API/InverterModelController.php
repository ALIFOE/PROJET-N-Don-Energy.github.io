<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InverterModelController extends Controller
{
    public function getModelsByBrand($brand)
    {
        // Liste des modèles par marque
        $models = [
            'sma' => [
                'sunny-boy-1.5' => 'Sunny Boy 1.5',
                'sunny-boy-2.0' => 'Sunny Boy 2.0',
                'sunny-boy-2.5' => 'Sunny Boy 2.5',
                'sunny-boy-3.0' => 'Sunny Boy 3.0',
                'sunny-boy-3.6' => 'Sunny Boy 3.6',
                'sunny-boy-4.0' => 'Sunny Boy 4.0',
                'sunny-boy-5.0' => 'Sunny Boy 5.0',
                'sunny-boy-6.0' => 'Sunny Boy 6.0'
            ],
            'fronius' => [
                'symo-3.0-3-m' => 'Symo 3.0-3-M',
                'symo-3.7-3-m' => 'Symo 3.7-3-M',
                'symo-4.5-3-m' => 'Symo 4.5-3-M',
                'symo-5.0-3-m' => 'Symo 5.0-3-M',
                'symo-6.0-3-m' => 'Symo 6.0-3-M',
                'primo-3.0-1' => 'Primo 3.0-1',
                'primo-3.6-1' => 'Primo 3.6-1',
                'primo-4.0-1' => 'Primo 4.0-1',
                'primo-4.6-1' => 'Primo 4.6-1',
                'primo-5.0-1' => 'Primo 5.0-1'
            ],
            'huawei' => [
                'sun2000-2ktl-l1' => 'SUN2000-2KTL-L1',
                'sun2000-3ktl-l1' => 'SUN2000-3KTL-L1',
                'sun2000-3.68ktl-l1' => 'SUN2000-3.68KTL-L1',
                'sun2000-4ktl-l1' => 'SUN2000-4KTL-L1',
                'sun2000-4.6ktl-l1' => 'SUN2000-4.6KTL-L1',
                'sun2000-5ktl-l1' => 'SUN2000-5KTL-L1',
                'sun2000-6ktl-l1' => 'SUN2000-6KTL-L1'
            ],
            'solaredge' => [
                'se2200h' => 'SE2200H',
                'se3000h' => 'SE3000H',
                'se3500h' => 'SE3500H',
                'se3680h' => 'SE3680H',
                'se4000h' => 'SE4000H',
                'se5000h' => 'SE5000H',
                'se6000h' => 'SE6000H'
            ],
            'growatt' => [
                'min-2500tl-xe' => 'MIN 2500TL-XE',
                'min-3000tl-xe' => 'MIN 3000TL-XE',
                'min-3600tl-xe' => 'MIN 3600TL-XE',
                'min-4200tl-xe' => 'MIN 4200TL-XE',
                'min-4600tl-xe' => 'MIN 4600TL-XE',
                'min-5000tl-xe' => 'MIN 5000TL-XE',
                'min-6000tl-xe' => 'MIN 6000TL-XE'
            ],
            'goodwe' => [
                'gw3000-ns' => 'GW3000-NS',
                'gw3600-ns' => 'GW3600-NS',
                'gw4200-ns' => 'GW4200-NS',
                'gw5000-ns' => 'GW5000-NS',
                'gw6000-ns' => 'GW6000-NS'
            ]
        ];

        if (!isset($models[strtolower($brand)])) {
            return response()->json([
                'success' => false,
                'message' => 'Marque non trouvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'models' => $models[strtolower($brand)]
        ]);
    }
}
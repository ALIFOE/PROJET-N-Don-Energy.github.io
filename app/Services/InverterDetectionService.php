<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class InverterDetectionService
{
    private array $detectionRules = [
        'sungrow' => [
            'port' => 502,
            'check' => 'checkSunGrow'
        ],
        'huawei' => [
            'check' => 'checkHuawei'
        ],
        'sma' => [
            'check' => 'checkSMA'
        ],
        'fronius' => [
            'check' => 'checkFronius'
        ],
        'schneider' => [
            'check' => 'checkSchneider'
        ],
        'abb' => [
            'check' => 'checkABB'
        ],
        'delta' => [
            'check' => 'checkDelta'
        ],
        'goodwe' => [
            'check' => 'checkGoodWe'
        ]
    ];

    public function detectInverter(): string
    {
        if (!config('inverters.auto_detection', true)) {
            return config('inverters.default', 'sungrow');
        }

        foreach ($this->detectionRules as $type => $rules) {
            if (method_exists($this, $rules['check']) && $this->{$rules['check']}()) {
                return $type;
            }
        }

        return config('inverters.default', 'sungrow');
    }

    private function checkSunGrow(): bool
    {
        $host = config('inverters.connections.sungrow.host');
        $port = config('inverters.connections.sungrow.port');

        try {
            $fp = @fsockopen($host, $port, $errno, $errstr, config('inverters.modbus.timeout', 5));
            if ($fp) {
                fclose($fp);
                return true;
            }
        } catch (\Exception $e) {
            \Log::info("Tentative de détection SunGrow échouée: " . $e->getMessage());
        }
        return false;
    }

    private function checkHuawei(): bool
    {
        $apiUrl = config('inverters.connections.huawei.api_url');
        $apiKey = config('inverters.connections.huawei.api_key');

        if (empty($apiKey)) return false;

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}"
            ])->get($apiUrl . '/system/status');

            return $response->successful();
        } catch (\Exception $e) {
            \Log::info("Tentative de détection Huawei échouée: " . $e->getMessage());
            return false;
        }
    }

    private function checkSMA(): bool
    {
        $ipAddress = config('inverters.connections.sma.ip_address');
        
        try {
            $response = Http::timeout(5)->get("https://{$ipAddress}/dyn/getDashValues.json");
            return $response->successful();
        } catch (\Exception $e) {
            \Log::info("Tentative de détection SMA échouée: " . $e->getMessage());
            return false;
        }
    }

    private function checkFronius(): bool
    {
        $ipAddress = config('inverters.connections.fronius.ip_address');
        $deviceId = config('inverters.connections.fronius.device_id');
        
        try {
            $response = Http::timeout(5)
                ->get("http://{$ipAddress}/solar_api/v1/GetInverterInfo.cgi", [
                    'DeviceId' => $deviceId
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            \Log::info("Tentative de détection Fronius échouée: " . $e->getMessage());
            return false;
        }
    }

    private function checkSchneider(): bool
    {
        $ipAddress = config('inverters.connections.schneider.ip_address');
        
        try {
            $response = Http::timeout(5)->get("http://{$ipAddress}/api/status");
            return $response->successful();
        } catch (\Exception $e) {
            \Log::info("Tentative de détection Schneider échouée: " . $e->getMessage());
            return false;
        }
    }

    private function checkABB(): bool
    {
        $ipAddress = config('inverters.connections.abb.ip_address');
        $port = config('inverters.connections.abb.port');
        $serialNumber = config('inverters.connections.abb.serial_number');

        try {
            $response = Http::timeout(5)
                ->get("http://{$ipAddress}:{$port}/v1/devices/{$serialNumber}/status");
            return $response->successful();
        } catch (\Exception $e) {
            \Log::info("Tentative de détection ABB échouée: " . $e->getMessage());
            return false;
        }
    }

    private function checkDelta(): bool
    {
        $ipAddress = config('inverters.connections.delta.ip_address');

        try {
            $response = Http::timeout(5)->get("http://{$ipAddress}/api/v1/status");
            return $response->successful();
        } catch (\Exception $e) {
            \Log::info("Tentative de détection Delta échouée: " . $e->getMessage());
            return false;
        }
    }

    private function checkGoodWe(): bool
    {
        $ipAddress = config('inverters.connections.goodwe.ip_address');
        $port = config('inverters.connections.goodwe.modbus_port');

        try {
            $fp = @fsockopen($ipAddress, $port, $errno, $errstr, config('inverters.modbus.timeout', 5));
            if ($fp) {
                fclose($fp);
                return true;
            }
        } catch (\Exception $e) {
            \Log::info("Tentative de détection GoodWe échouée: " . $e->getMessage());
        }
        return false;
    }
}
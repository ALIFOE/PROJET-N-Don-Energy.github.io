<?php

return [
    'default' => env('DEFAULT_INVERTER', 'sungrow'),

    'connections' => [
        'sungrow' => [
            'host' => env('SUNGROW_HOST', '192.168.1.100'),
            'port' => env('SUNGROW_PORT', 502),
        ],
        
        'huawei' => [
            'api_url' => env('HUAWEI_API_URL', 'https://api.huawei.com/solar'),
            'api_key' => env('HUAWEI_API_KEY'),
        ],
        
        'sma' => [
            'ip_address' => env('SMA_IP_ADDRESS', '192.168.1.101'),
            'password' => env('SMA_PASSWORD'),
        ],

        'fronius' => [
            'ip_address' => env('FRONIUS_IP_ADDRESS', '192.168.1.102'),
            'device_id' => env('FRONIUS_DEVICE_ID', '1'),
        ],

        'schneider' => [
            'ip_address' => env('SCHNEIDER_IP_ADDRESS', '192.168.1.103'),
            'username' => env('SCHNEIDER_USERNAME', 'admin'),
            'password' => env('SCHNEIDER_PASSWORD'),
        ],

        'abb' => [
            'ip_address' => env('ABB_IP_ADDRESS', '192.168.1.104'),
            'port' => env('ABB_PORT', 502),
            'serial_number' => env('ABB_SERIAL_NUMBER'),
        ],

        'delta' => [
            'ip_address' => env('DELTA_IP_ADDRESS', '192.168.1.105'),
            'username' => env('DELTA_USERNAME', 'admin'),
            'password' => env('DELTA_PASSWORD'),
        ],

        'goodwe' => [
            'ip_address' => env('GOODWE_IP_ADDRESS', '192.168.1.106'),
            'serial_number' => env('GOODWE_SERIAL_NUMBER'),
            'modbus_port' => env('GOODWE_MODBUS_PORT', 502),
        ],
    ],

    'update_interval' => env('INVERTER_UPDATE_INTERVAL', 30),
    
    'auto_detection' => env('INVERTER_AUTO_DETECTION', true),

    'modbus' => [
        'timeout' => env('MODBUS_TIMEOUT', 5),
        'retries' => env('MODBUS_RETRIES', 3),
    ],
];
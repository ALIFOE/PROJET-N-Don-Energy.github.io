<?php

return [
    'notify_on' => [
        'order' => [
            'created' => true,
            'paid' => true,
            'cancelled' => true,
            'shipped' => true,
        ],
        'devis' => [
            'created' => true,
            'accepted' => true,
            'rejected' => true,
        ],
        'service' => [
            'requested' => true,
            'status_changed' => true,
        ],
        'formation' => [
            'inscription' => true,
            'canceled' => true,
        ],
        'user' => [
            'created' => true,
            'role_changed' => true,
            'deleted' => true,
        ],
    ],
    
    'channels' => [
        'mail' => true,
        'database' => true,
    ],
    
    'queue' => [
        'enabled' => true,
        'connection' => 'database',
        'queue' => 'notifications',
    ],
];

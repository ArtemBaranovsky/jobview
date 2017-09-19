<?php
return [
    'gearman' => [
        'servers' => [
            'master' => '127.0.0.1:4730',
            'slave'  => '127.0.0.1:4730',
        ],
    ],
    'vk' => [
        'access_token' => '',
        'chat' => [
            'support'       => 0,
            'registration'  => 0,
            'payment'       => 3,
        ],
        'testAccount' => [
            'id'         => 0,
            'first_name' => '',
            'last_name'  => '',
            'domain'     => '',
        ]
    ]
];

<?php
return [
    'domain'                => 'https://domain.loc/',
    'email'                 => 'noreply@domain.loc',
    'expireRecovery'        => 86400,
    'loginDuration'         => 3600 * 24 * 30,
    'assetPath'             => '@themes/lte/assets/',

    'interkassa' => [
        'id'   => 'SET_DATA',
        'sign' => 'SET_DATA',
    ],

    'webMoney' => [
        'purse'      => 'SET_DATA',
        'secret_key' => 'SET_DATA'
    ],

    'walletOne' => [
        'url'           => 'https://wl.walletone.com/checkout/checkout/Index',
        'auto_location' => '0',
        'currency_id'   => '643',
        'merchant_id'   => 'SET_DATA',
        'signature'     => 'SET_DATA',
    ],

    'anticaptcha' => [
        'services' => 'anti-captcha',
        'api_key'  => 'SET_DATA'
    ],

    'sendPulse' => [
        'smtp' => [
            'public_key' => 'SET_DATA'
        ]
    ]
];

<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
    ],
    'controllerNamespace' => 'console\controllers',
    'modules' => [
        'users' => [
            'class' => 'modules\users\Module',
        ],
    ],
    'components' => [
        'log' => [
            'flushInterval' => 1,
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\DbTarget',
                    'exportInterval' => 1,
                    'levels' => ['info', 'error', 'warning'],
                    'logTable' => '{{%app_log}}',
                    'except' => [
                        'application',
                        'yii\db\Command*',
                        'yii\db\Connection*',
                        'yii\web\HttpException:404'
                    ],
                ],
            ],
        ],
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationNamespaces' => [
                'modules\users\migrations',
            ],
            'migrationPath' => null
        ],
    ],
    'params' => $params,
];

<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'frontend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'language' => 'ru',
    'bootstrap' => [
        'log',
        'modules\main\Bootstrap',
        'modules\users\Bootstrap',
    ],
    'modules' => [
        'main' => ['class' => 'modules\main\Module'],
        'users' => ['class' => 'modules\users\Module']
    ],
    'components' => [
        'view' => [
            'theme' => 'themes\lte\Theme'
        ],
        'request' => [
            'baseUrl' => '',
            'enableCsrfCookie' => false
        ],
        'user' => [
            'identityClass' => 'modules\users\models\frontend\Users',
            'enableAutoLogin' => true,
            'loginUrl' => ['/users/default/login']
        ],
        'errorHandler' => [
            'errorAction' => 'main/default/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error', 'warning'],
                    'logTable' => '{{%app_log}}',
                    'except' => [
                        'yii\db\Command*',
                        'yii\db\Connection*',
                        'yii\web\HttpException:404'
                    ],
                    'prefix' => function ($message) {
                        return '[' . Yii::$app->user->id . ']';
                    }
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [],
        ],
    ],
    'params' => $params,

    'as access' => [
        'class' => 'yii\filters\AccessControl',
        'rules' => [
            [
                'allow'       => true,
                'actions'     => ['index'],
                'controllers' => ['main/default'],
                'roles'       => ['?'],
            ],
            [
                'allow'       => true,
                'actions'     => [
                    'login',
                    'registration',
                    'password-recovery',
                    'set-password',
                    'activate',
                    'unsubscribe-news',
                    'unsubscribe-notifications'
                ],
                'controllers' => ['users/default'],
                'roles'       => ['?'],
            ],
            [
                'allow'       => false,
                'actions'     => ['login', 'registration', 'password-recovery', 'set-password'],
                'controllers' => ['users/default'],
                'roles'       => ['@'],
            ],
            [
                'allow'       => true,
                'roles'       => ['@'],
            ],
            [
                'allow'       => true,
                'actions'     => ['error'],
                'controllers' => ['main/default'],
            ],
        ],
    ],
];
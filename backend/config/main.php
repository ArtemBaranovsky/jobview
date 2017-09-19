<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id'        => 'backend',
    'basePath'  => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules'   => [
        'main'    => ['class' => 'modules\main\Module'],
        'rbac'    => ['class' => 'modules\rbac\Module'],
        'users'   => ['class' => 'modules\users\Module'],
    ],
    'components' => [
        'request' => [
            'baseUrl'          => '/backend',
            'enableCsrfCookie' => false,
        ],
        'view' => [
            'theme' => 'themes\lte\Theme'
        ],
        'user' => [
            'identityClass'   => 'modules\users\models\BaseUsers',
            'enableAutoLogin' => true,
            'loginUrl'        => ['/users/default/login'],
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
    ],
    'as access' => [
        'class' => 'yii\filters\AccessControl',
        'rules' => [
            [
                'allow' => true,
                'roles' => ['backend-access'],
            ],
            [
                'allow'       => true,
                'actions'     => ['login'],
                'controllers' => ['users/default'],
                'roles'       => ['?'],
            ],
            [
                'allow'       => true,
                'controllers' => ['debug/default'],
                'roles'       => ['?'],
            ],
        ],
    ],

    'params' => $params,
];


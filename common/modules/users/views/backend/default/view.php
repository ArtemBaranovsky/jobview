<?php

use yii\helpers\Html;
use yii\grid\GridView;
use modules\users\Module;
use yii\widgets\DetailView;
use modules\users\models\backend\Log;
use modules\users\models\backend\Users;

/* @var $this yii\web\View */
/* @var $model Users */
/* @var $log \yii\data\ArrayDataProvider */
/* @var $payment \yii\data\ArrayDataProvider */
/* @var $account \yii\data\ArrayDataProvider */
/* @var $dataProvider \yii\data\ArrayDataProvider */

$this->title                     = 'Пользователь ' . $model->email;
$this->params['pageTitle']       = $model->email;

$this->params['place']           = 'users';
$this->params['pageIcon']        = 'users';
$this->params['breadcrumbs'][]   = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][]   = $model->email;

$actions   = false;
$columns   = [];
$columns[] = 'id';
$columns[] = 'name';
$columns[] = 'email';
$columns[] = 'time_last_visit:datetime';
$columns[] = 'time_registration:datetime';
$columns[] = [
    'attribute' => 'activate_time',
    'format'    => 'datetime',
    'value'     => $model->activate_time > 0 ? $model->activate_time : null
];

if ($model->utm_source){
    $columns[] = [
        'attribute' => 'utm_source',
        'contentOptions' => ['style' => 'background-color: #FBF4F4'],
        'captionOptions' => ['style' => 'background-color: #FBF4F4']
    ];
    $columns[] = [
        'attribute' => 'utm_medium',
        'contentOptions' => ['style' => 'background-color: #FBF4F4'],
        'captionOptions' => ['style' => 'background-color: #FBF4F4']
    ];
    $columns[] = [
        'attribute' => 'utm_campaign',
        'contentOptions' => ['style' => 'background-color: #FBF4F4'],
        'captionOptions' => ['style' => 'background-color: #FBF4F4']
    ];

    if ($model->utm_term){
        $columns[] = [
            'attribute' => 'utm_term',
            'contentOptions' => ['style' => 'background-color: #FBF4F4'],
            'captionOptions' => ['style' => 'background-color: #FBF4F4']
        ];
    }
    if ($model->utm_content){
        $columns[] = [
            'attribute' => 'utm_content',
            'contentOptions' => ['style' => 'background-color: #FBF4F4'],
            'captionOptions' => ['style' => 'background-color: #FBF4F4']
        ];
    }
}
?>

<!-- Информация -->
<div class="box">
    <div class="box-header">

        <?php if (Yii::$app->user->can('users-crud') || Yii::$app->user->can('users-auth')): ?>
            <div class="btn-group">
                <?php if (Yii::$app->user->can('users-crud')): ?>
                    <?= Html::a('<i class="fa fa-edit"></i> ' . Yii::t('app', 'BTN_UPDATE'), [
                        'update',
                        'id'  => $model->id,
                        'ref' => 1
                    ], ['class' => 'btn btn-success']
                    ) ?>

                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <?php if (Yii::$app->user->can('users-auth')): ?>
                            <?php $actions = true ?>
                            <li>
                                <?= Html::a('<i class="fa fa-key"></i> Авторизация', ['auth', 'id' => $model->id], [
                                    'data' => [
                                        'method' => 'post',
                                    ]
                                ]) ?>
                            </li>
                        <?php endif; ?>
                        <?php if ($model->activate_time < 1): ?>
                            <?php $actions = true ?>
                            <li>
                                <?= Html::a('<i class="fa fa-check"></i> Активировать уч. запись',
                                    ['activate-email', 'id' => $model->id],
                                    [
                                        'data' => [
                                            'method' => 'post',
                                        ]
                                    ]
                                ) ?>
                            </li>
                        <?php endif; ?>
                    </ul>
                <?php else: ?>
                    <?= Html::a('<i class="fa fa-key"></i> Авторизация', [
                        'auth',
                        'id' => $model->id
                    ], [
                            'class' => 'btn btn-success',
                            'data'  => [
                                'method' => 'post',
                            ]
                        ]
                    ) ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="box-body no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => $columns,
        ]) ?>
    </div>
</div>

<!-- Журнал действий -->
<div class="box">
    <div class="box-header">
        <h3>Журнал действий</h3>
    </div>
    <div class="box-body no-padding">
        <?= GridView::widget([
            'dataProvider' => $log,
            'columns' => [
                [
                    'attribute' => 'message',
                    'value'     => function ($model){
                        /* @var $model Log */
                        $params = json_decode($model->params, true);

                        return Module::t('log', $model->message, $params);
                    }
                ],
                [
                    'attribute'      => 'created_at',
                    'format'         => 'datetime',
                    'headerOptions'  => ['data-hide' => 'xs'],
                ],
                [
                    'class'          => 'yii\grid\ActionColumn',
                    'template'       => '<div class="text-center">{view}</div>',
                    'urlCreator'     => function ($action, $model){
                        return ['/users/log/view', 'id' => $model->id];
                    },
                    'headerOptions'  => [
                        'data-hide'  => 'xs,sm',
                        'data-name'  => Yii::t('app', 'LABEL_ACTIONS')
                    ],
                ],
                [
                    'headerOptions'  => ['data-class' => 'expand', 'data-hide' => 'md, lg, pc, other'],
                    'value' => function(){
                        return '';
                    },
                ],
            ]
        ]) ?>
    </div>
</div>
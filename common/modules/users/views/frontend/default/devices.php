<?php

use yii\helpers\Html;
use yii\grid\GridView;
use modules\users\Module;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                     = 'Устройства';
$this->params['pageTitle']       = $this->title;

$this->params['place']           = 'users';
$this->params['pageIcon']        = 'desktop';
$this->params['breadcrumbs'][]   = $this->title;
?>

<div class="box">
    <?php if ($dataProvider->totalCount > 0): ?>
        <div class="box-header">
            <?= Html::a('<i class="fa fa-times"></i> ' . Module::t('main', 'BTN_CLEAR_DEVICES'), ['devices-clear'], [
                'class' => 'btn btn-danger',
                'data'  => [
                    'toggle'        => 'confirm',
                    'method'        => 'post',
                    'title'         => Yii::t('app', 'CONFIRM_TITLE'),
                    'description'   => Module::t('main', 'CONFIRM_DELETE_DEVICES_CLEAR_DESCRIPTION'),
                ]
            ]) ?>
        </div>
    <?php endif ?>

    <div class="box-body no-padding">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'ip',
                [
                    'attribute'      => 'ua',
                    'headerOptions'  => ['data-hide'  => 'xs'],
                ],
                [
                    'attribute'      => 'last_action',
                    'format'         => 'datetime',
                    'headerOptions'  => ['data-hide'  => 'xs'],
                ],
                [
                    'class'          => 'common\widgets\ActionColumn',
                    'firstButton'    => 'devices-delete',
                    'hiddenButtons'  => [],
                    'headerOptions'  => [
                        'data-hide'  => 'xs,sm,md',
                        'data-name'  => Yii::t('app', 'LABEL_ACTIONS')
                    ],
                    'buttons' => [
                        'devices-delete' => function ($key, $model){
                            /* @var $model \modules\users\models\BaseSession */

                            $title       = Yii::t('app', 'CONFIRM_TITLE');
                            $description = Module::t('main', 'CONFIRM_DELETE_DEVICE_DESCRIPTION');

                            $options = [
                                'toggle'        => 'confirm',
                                'method'        => 'post',
                                'title'         => $title,
                                'description'   => $description,
                            ];

                            return Html::a('<i class="fa fa-times"></i> ' . Module::t('main', 'BTN_DEVICES_DELETE'), ['devices-delete', 'id' => $model->id], [
                                'class'    => 'btn btn-primary btn-xs btn-flat',
                                'data'     => $options,
                                'disabled' => Yii::$app->session->id == $model->id
                            ]);
                        }
                    ],
                ],
                [
                    'headerOptions'  => ['data-class' => 'expand', 'data-hide' => 'lg, pc, other'],
                    'value' => function(){
                        return '';
                    },
                ],
            ],
        ]); ?>
    </div>
</div>
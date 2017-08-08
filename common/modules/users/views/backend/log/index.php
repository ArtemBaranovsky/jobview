<?php

use yii\helpers\Html;
use yii\grid\GridView;
use modules\users\Module;
use modules\users\models\backend\Log;

/* @var $this yii\web\View */
/* @var $searchModel modules\users\models\backend\search\Log */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                     = 'Журнал действий';
$this->params['pageTitle']       = $this->title;

$this->params['place']           = 'users-log';
$this->params['pageIcon']        = 'retweet';
$this->params['breadcrumbs'][]   = $this->title;
?>

<div class="box">
    <div class="box-body no-padding">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute'      => 'user_id',
                    'format'         => 'html',
                    'headerOptions'  => ['data-hide' => 'xs,sm'],
                    'value' => function ($model){
                        /* @var $model Log */
                        $user = $model->user;

                        return $user ?
                            Html::a(Html::encode($user->email), ['/users/default/view', 'id' => $user->id]) : null;
                    }
                ],
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
                    'headerOptions'  => [
                        'data-hide'  => 'xs,sm,md',
                        'data-name'  => Yii::t('app', 'LABEL_ACTIONS')
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
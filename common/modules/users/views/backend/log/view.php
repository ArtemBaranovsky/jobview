<?php

use yii\helpers\Html;
use yii\grid\GridView;
use modules\users\Module;
use yii\widgets\DetailView;
use modules\users\models\backend\Log;

/* @var $this yii\web\View */
/* @var $model Log */
/* @var $dataProvider \yii\data\ArrayDataProvider */

$action = Module::t('log', $model->message, json_decode($model->params, true));

$this->title                     = 'Журнал действий';
$this->params['pageTitle']       = $this->title;

$this->params['place']           = 'users-log';
$this->params['pageIcon']        = 'retweet';
$this->params['breadcrumbs'][]   = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][]   = $action;
?>

<div class="box">
    <div class="box-body no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'attribute' => 'user_id',
                    'format'    => 'html',
                    'value'     => $model->user === null ?
                        null : Html::a(Html::encode($model->user->email), [
                            '/users/default/view', 'id' => $model->user_id
                        ])
                ],
                [
                    'attribute' => 'message',
                    'format'    => 'raw',
                    'value'     => $action
                ],
                'url:url',
                'created_at:datetime',
                'ip',
                'ua',
            ],
        ]) ?>

        <h3>Изменения</h3>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => 'key',
                    'label'     => 'Поле',
                    'format'    => 'html',
                    'value'     => function ($model){
                        return Html::tag('strong', $model['key']);
                    }
                ],
                [
                    'attribute' => 'old',
                    'format'    => 'html',
                    'label'     => 'Старые данные',
                    'visible'   => in_array($model->type, [Log::TYPE_UPDATE, Log::TYPE_DELETE])
                ],
                [
                    'attribute' => 'new',
                    'format'    => 'html',
                    'label'     => 'Новые данные',
                    'visible'   => $model->type != Log::TYPE_DELETE
                ],
            ]
        ]); ?>

    </div>
</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Роли пользователей';
$this->params['pageTitle'] = $this->title;
$this->params['pageIcon'] = 'puzzle-piece';
$this->params['place'] = 'roles';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box">

    <?= Html::beginForm(['delete-multiple']); ?>

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Добавить', ['create'], [
            'class' => 'btn btn-primary'
        ]) ?>
        <?= Html::submitButton('<i class="fa fa-times"></i> Удалить', [
            'class' => 'btn btn-danger'
        ]) ?>
    </div>

    <div class="box-body no-padding">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\CheckboxColumn'],
                [
                    'attribute' => 'name',
                    'label' => 'Алиас',
                    'value' => 'name',
                ],
                [
                    'attribute' => 'description',
                    'label' => 'Название',
                    'value' => 'description',
                ],
                [
                    'class'          => 'common\widgets\ActionColumn',
                    'firstButton'    => 'update',
                    'hiddenButtons'  => ['delete'],
                ]
            ]
        ]) ?>
    </div>

    <?= Html::endForm(); ?>

</div>
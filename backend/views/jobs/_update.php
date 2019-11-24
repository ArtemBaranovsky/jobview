<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $model common\models\Job */

$this->title = 'Update Job: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Jobs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="job-update">

<?
Modal::begin([
    'header' => '<h2>Редактирование вакансии</h2>',
    'toggleButton' => ['label' => 'click me'],
    'footer' => 'Низ окна',
]); ?>

<h1><?= Html::encode($this->title) ?></h1>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

<? Modal::end();
?>
</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Job */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Jobs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs(
    "$('modal').ready(function(){
//		    var modal = $('#modal').dialog();
//		    modal.on('dialogclose', function(){
            $('.btn-success').on( 'click', function() {
                    console.log('Окно закрыто');
                    $('#modal').modal('hide');
                    $.pjax.reload({'container' : '#pjax-container'});
            })
})"
);

?>
<!--плагин Reveal-->

<div class="job-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <? Modal::begin([
            'header' => '<h2>Редактирование вакансии</h2>',
            'toggleButton' => [
                'id' => 'modal',
                'label' => 'Update',
                'class' => 'btn btn-primary',
            ],
            'footer' => 'Низ окна',
        ]); ?>

            <h1><?= Html::encode($this->title) ?></h1>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        <? Modal::end(); ?>

        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php Pjax::begin(); ?>
    <?= DetailView::widget([
        'id' => 'pjax-container',
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'salary',
            'employment.title',
        ],
    ]) ?>
    <?php Pjax::end(); ?>


</div>

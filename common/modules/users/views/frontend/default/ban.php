<?php

use yii\helpers\Url;
use yii\helpers\Html;
use modules\users\Module;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \modules\users\models\frontend\Users */

$this->title = 'Доступ заблокирован';
$assetPath   = Yii::$app->assetManager->getPublishedUrl(Yii::$app->params['assetPath']);
?>

<div class="login-box">
    <div class="login-logo">
        <a href="/">
            <b>
                <?= Yii::$app->name ?>
            </b>
        </a>
    </div><!-- /.login-logo -->
    <div class="login-box-body">
        <?php $form = ActiveForm::begin(); ?>

        <div class="guest-confirm">
            <h4 class="text-red">
                Доступ заблокирован!
            </h4>
        </div>

        <p>
            <strong>Причина:</strong> <?= nl2br(Html::encode($model->ban_description)) ?>
        </p>

        <p>
            <strong>Контакты:</strong> support@hamster.pro
        </p>

        <div class="mt-15">
            <a href="<?= Url::to(['/users/default/logout']) ?>" data-method="post" class="btn btn-primary btn-block">
                Выход
            </a>
        </div>

        <?php ActiveForm::end(); ?>

    </div><!-- /.login-box-body -->

</div><!-- /.login-box -->
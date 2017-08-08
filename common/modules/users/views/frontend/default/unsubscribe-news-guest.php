<?php

use yii\helpers\Url;
use modules\users\Module;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \modules\users\models\frontend\Users */

$this->title = Module::t('main', 'TITLE_UNSUBSCRIBE_NEWS');
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
        <p class="login-box-msg">
            <?= Module::t('main', $this->title) ?>
        </p>

        <?php $form = ActiveForm::begin(); ?>

        <div class="guest-confirm">
            <div>
                <?= Module::t('main', 'MESSAGE_UNSUBSCRIBE_NEWS_YOUR_EMAIL_TITLE_{email}', [
                    'email' => $model->email
                ]) ?>
            </div>

            <h4>
                <?= Module::t('main', 'MESSAGE_UNSUBSCRIBE_NEWS_CONFIRM_TITLE') ?>
            </h4>

            <button class="btn btn-primary">
                <i class="fa fa-check"></i>
                <?= Module::t('main', 'MESSAGE_UNSUBSCRIBE_NEWS_CONFIRM_BTN_SUCCESS') ?>
            </button>
            <a href="<?= Url::to(['/']) ?>" class="btn btn-danger">
                <i class="fa fa-times"></i>
                <?= Module::t('main', 'MESSAGE_UNSUBSCRIBE_NEWS_CONFIRM_BTN_CANCEL') ?>
            </a>
        </div>

        <div class="menu">
            <a href="<?= Url::to(['/users/default/login']) ?>" class="text-center">
                <?= Module::t('main', 'TITLE_LOGIN') ?>
            </a>
            <a href="<?= Url::to(['/users/default/registration']) ?>" class="pull-right">
                <?= Module::t('main', 'TITLE_REGISTRATION') ?>
            </a>
        </div>

        <?php ActiveForm::end(); ?>

    </div><!-- /.login-box-body -->

    <div class="policy">
        <a href="/" class="pull-left">
            <?= Module::t('main', 'TITLE_HOME') ?>
        </a>
        <a href="#" data-toggle="modal" data-target="#modal-policy" class="pull-right">
            <?= Module::t('main', 'TITLE_POLICY') ?>
        </a>
    </div>

</div><!-- /.login-box -->
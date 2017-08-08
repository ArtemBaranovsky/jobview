<?php

use yii\helpers\Url;
use yii\helpers\Html;
use modules\users\Module;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \modules\users\models\frontend\forms\PasswordRecovery */

$this->title = Module::t('main', 'TITLE_PASSWORD_RECOVERY');
$this->params['breadcrumbs'][] = $this->title;
$assetPath = Yii::$app->assetManager->getPublishedUrl(Yii::$app->params['assetPath']);
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
            <?= Module::t('main', 'TITLE_PASSWORD_RECOVERY') ?>
        </p>

        <div class="recovery-message">
            <?= Module::t('main', 'MESSAGE_RECOVERY_PASSWORD_SEND_{email}', ['email' => $model->email]) ?>
        </div>

        <div class="menu">
            <a href="<?= Url::to(['/users/default/login']) ?>" class="text-center">
                <?= Module::t('main', 'TITLE_LOGIN') ?>
            </a>
            <a href="<?= Url::to(['/users/default/registration']) ?>" class="pull-right">
                <?= Module::t('main', 'TITLE_REGISTRATION') ?>
            </a>
        </div>

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
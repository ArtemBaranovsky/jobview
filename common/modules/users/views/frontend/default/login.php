<?php

use yii\helpers\Url;
use yii\helpers\Html;
use modules\users\Module;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \modules\users\models\frontend\forms\Login */

$this->title = Module::t('main', 'TITLE_LOGIN');
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
            <?= Module::t('main', 'TITLE_LOGIN_DESCRIPTION') ?>
        </p>

        <?php if ($model->hasErrors()): ?>
            <div class="callout callout-danger">
                <i class="icon fa fa-ban"></i>&nbsp;&nbsp;<?= current($model->getFirstErrors()) ?>
            </div>
        <?php endif; ?>

        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group has-feedback">
            <?= Html::activeTextInput($model, 'email', [
                'class' => 'form-control',
                'placeholder' => Module::t('main', 'ATTR_EMAIL')
            ]) ?>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <?= Html::activePasswordInput($model, 'password', [
                'class' => 'form-control',
                'placeholder' => Module::t('main', 'ATTR_PASSWORD')
            ]) ?>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <?= Html::submitButton(Module::t('main', 'BTN_LOGIN'), [
                    'class' => 'btn btn-primary btn-block btn-flat btn-loader',
                    'name' => 'login-button',
                ]) ?>
            </div>
        </div>

        <div class="menu">
            <a href="<?= Url::to(['/users/default/registration']) ?>" class="text-center">
                <?= Module::t('main', 'TITLE_REGISTRATION') ?>
            </a>
            <a href="<?= Url::to(['/users/default/password-recovery']) ?>" class="pull-right">
                <?= Module::t('main', 'PASSWORD_RECOVERY') ?>
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
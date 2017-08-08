<?php

use yii\helpers\Url;
use modules\users\Module;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \modules\users\models\frontend\Users */

$this->title                     = Module::t('main', 'TITLE_UNSUBSCRIBE_NOTIFICATIONS');
$this->params['pageIcon']        = 'rss';
$this->params['pageTitle']       = $this->title;
$this->params['breadcrumbs'][]   = $this->title;
?>

<div class="account-create">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-confirm">
        <div class="box-body">
            <h3>
                <?= Module::t('main', 'MESSAGE_UNSUBSCRIBE_NOTIFICATIONS_CONFIRM_TITLE') ?>
            </h3>
        </div>
        <div class="box-footer">
            <button class="btn btn-primary">
                <i class="fa fa-check"></i>
                <?= Module::t('main', 'MESSAGE_UNSUBSCRIBE_NOTIFICATIONS_CONFIRM_BTN_SUCCESS') ?>
            </button>
            <a href="<?= Url::to(['/']) ?>" class="btn btn-danger">
                <i class="fa fa-times"></i>
                <?= Module::t('main', 'MESSAGE_UNSUBSCRIBE_NOTIFICATIONS_CONFIRM_BTN_CANCEL') ?>
            </a>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
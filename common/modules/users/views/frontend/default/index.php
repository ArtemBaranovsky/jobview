<?php

use yii\helpers\Html;
use \modules\users\Module;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \modules\users\models\frontend\Users */

$this->title                     = Yii::t('app', 'LINK_SETTING');
$this->params['pageIcon']        = 'user';
$this->params['place']           = 'user';
$this->params['pageTitle']       = $this->title;
$this->params['breadcrumbs'][]   = $this->title;
$model->subscribe_news           = $model->subscribe_news === null ? 0 : 1;
$model->subscribe_notifications  = $model->subscribe_notifications === null ? 0 : 1;
?>

<div class="account-create">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box">
        <div class="box-body">
            <div class="col-md-5">
                <div class="row">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => 50]) ?>
                    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
                    <?= $form->field($model, 'password_old')->passwordInput(['maxlength' => 255]) ?>
                    <?= $form->field($model, 'password_new')->passwordInput(['maxlength' => 255]) ?>
                    <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => 255]) ?>

                    <h3 style="padding-left: 0px;">
                        <?= Module::t('main', 'TITLE_PROFILE_NOTIFICATION') ?>
                    </h3>

                    <?= $form->field($model, 'subscribe_news', [
                        'options' => [
                            'class' => 'form-group input-checkbox mt25',
                        ]
                    ])->checkbox(['class' => 'iCheck']) ?>

                    <?= $form->field($model, 'subscribe_notifications', [
                        'options' => [
                            'class' => 'form-group input-checkbox mt25',
                        ]
                    ])->checkbox(['class' => 'iCheck']) ?>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'BTN_SAVE'),
                            ['class' => 'btn btn-primary']
                        ) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
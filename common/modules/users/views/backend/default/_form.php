<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use modules\users\models\backend\Users;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model Users */

$this->registerJs('
    $("#users-ban_exists").on("change", function (){
        if ($(this).prop("checked") == true){
            $("#show-ban-description").show();
        }else{
            $("#show-ban-description").hide();
        }
    });
');
?>

<?php $form = ActiveForm::begin(); ?>

<div class="box">
    <div class="box-body">
        <div class="col-md-5">
            <div class="row">

                <?= $form->field($model, 'name') ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'password_new')->passwordInput() ?>

                <?= $form->field($model, 'password_repeat')->passwordInput() ?>

                <?php if ($model->scenario == Users::SCENARIO_UPDATE): ?>
                    <?= $form->field($model, 'ban_exists')->checkbox() ?>

                    <div id="show-ban-description" style="display: <?= $model->ban_exists == 1 ? 'block' : 'none' ?>">
                        <?= $form->field($model, 'ban_description')->textarea() ?>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'BTN_CREATE') : Yii::t('app', 'BTN_UPDATE'),
                        ['class' => 'btn btn-primary']
                    ) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
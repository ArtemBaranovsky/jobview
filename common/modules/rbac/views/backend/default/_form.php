<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \modules\rbac\models\PermissionsForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<?php $form = ActiveForm::begin(); ?>
    <div class="box">
        <div class="box-body">
            <div class="col-md-5">
                <div class="row">
                    <?= $form->field($model, 'rule')->textInput([
                        'maxlength' => 50,
                        'disabled' => $model->scenario == 'update' ? true : false
                    ]) ?>

                    <?= $form->field($model, 'name')->textInput(['maxlength' => 50]) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
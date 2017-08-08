<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \modules\rbac\models\RolesForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<?php $form = ActiveForm::begin(); ?>
<div class="box">
    <div class="box-body">
        <div class="col-md-5">
            <div class="row">

                <?= $form->field($model, 'namespace')->textInput(['maxlength' => 50]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                </div>

            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
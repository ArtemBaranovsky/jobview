<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Job */
/* @var $form yii\widgets\ActiveForm */
?>

<?php // обновляем грид под формой с оплатой внутри модального окна
$this->registerJs(
    '$("document").ready(function(){
            $("#new_payment").on("pjax:end", function() {
            $.pjax.reload({container:"#payments"});  //Reload GridView
        });
    });'
);
?>

<div class="payment-form">
    <?php yii\widgets\Pjax::begin(['id' => 'new_payment']) ?>
    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true], 'layout' => 'horizontal']); ?>

    <div class="row clearfix">
        <div class="col-md-12">
            <?= $form->field($model, 'title')->textInput(['value'=> $model->title])->label(false); ?>
            <div class="form-group field-payment-date required">
                <label class="control-label col-sm-3" for="title">Работа </label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <?= $model->title ?>
                    </div>
                </div>
            </div>



            <?= $form->field($model, 'employment_id')->dropDownList($model->getEmploymentList(), ['prompt'=>'- Выберите тип занятости -']) ?>


<!--            --><?//= $form->field($model, 'price', ['template' => '
//               {label}
//               <div class="col-sm-6">
//                   <div class="input-group">
//                      {input}
//                      <div class="input-group-addon">
//                         <span class="glyphicon glyphicon-rub"></span>
//                      </div>
//                   </div>
//                   {error}{hint}
//               </div>'])->textInput() ?>


        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-8">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <?php yii\widgets\Pjax::end(); ?>
</div>
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $model common\models\Job */
/* @var $form yii\widgets\ActiveForm */
?>

<?php // обновляем грид под формой с оплатой внутри модального окна
$this->registerJs(
    "$('modal').ready(function(){
            $('.btn-success').click(function() {
                event.preventDefault ();
                var newFields ={
                    'id': $('input#job-id').val(),
                    'title': $('input#job-title').val(),
                    'salary': $('input#job-salary').val(),
                    'employment_id': $('option:selected').attr('value')
                };
                sendAjaxForm(newFields);
        });
        
    function sendAjaxForm(newFields){
        var send = {
            'fields' : newFields,
                    '_csrf' :  $(\"[name='csrf-token']\").attr('content')
                };

           console.log(JSON.stringify(send));

          $.ajax({
                url: 'update-ajax',
                type: 'POST',
                dataType: 'json',
                data: JSON.stringify(send),
                success : function (result) {
//                     obj = JSON.parse(JSON.stringify(result));
//                    console.log(obj);
        },
                error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.statusText +' '+xhr.responseText+ ' '+
                xhr.status + ' ' +   thrownError);
        }
            });
        }
    
    });"
);
$this->registerJs($script, yii\web\View::POS_READY);

?>

<div class="job-form">
    <?php Pjax::begin(); ?>

    <?php $form = ActiveForm::begin(['options' => [
        'id' => 'pjax-container',
        'data-pjax' => true,
        'ajaxDataType' => 'json',

//        'class'=>'form form-register1 text-left'
    ]]); ?>
    <?= $form->field($model, 'id')->hiddenInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'salary')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'employment_id')->dropDownList($model->getEmploymentList(), ['prompt'=>'- Выберите тип занятости -']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php Pjax::end(); ?>

</div>

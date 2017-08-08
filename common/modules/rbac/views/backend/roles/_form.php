<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use modules\rbac\models\RolesForm;

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

                <?= $form->field($model, 'alias')->textInput(
                    [
                        'maxlength' => 50,
                        'disabled' => $model->scenario === $model::SCENARIO_UPDATE ? true : false
                    ])
                ?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => 50]) ?>

                <?php if (($rules = RolesForm::getRules()) != null): ?>
                    <?= $form->field($model, 'rule')->dropDownList($rules, [
                        'class'  => 'form-control select2',
                        'style'  => 'width: 100%;',
                    ]) ?>
                <?php endif; ?>

                <?php if (($permissions = RolesForm::getPermissions()) != null): ?>
                    <?= $form->field($model, 'permissions')->checkboxList($permissions, [
                        'item' => function ($index, $label, $name, $checked, $value) {
                            $checked = RolesForm::isChild(Yii::$app->request->get('id'), $value);
                            $checkbox = Html::checkbox($name, $checked, [
                                'label' => $label,
                                'value' => $value,
                            ]);
                            return Html::tag('div', $checkbox, [
                                'class' => 'checkbox'
                            ]);
                        }
                    ]) ?>
                <?php endif; ?>

                <?php if (($roles = RolesForm::getRoles(Yii::$app->request->get('id'))) != null): ?>
                    <?= $form->field($model, 'child_roles')->checkboxList($roles, [
                        'item' => function ($index, $label, $name, $checked, $value) {
                            $checked = RolesForm::isChild(Yii::$app->request->get('id'), $value);
                            $checkbox = Html::checkbox($name, $checked, [
                                'label' => $label,
                                'value' => $value,
                            ]);
                            return Html::tag('div', $checkbox, [
                                'class' => 'checkbox'
                            ]);
                        }
                    ]) ?>
                <?php endif; ?>

                <div class="form-group">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                </div>

            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
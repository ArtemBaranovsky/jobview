<?php

namespace common\widgets;

use yii\base\Model;
use yii\base\Widget;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * Class RangeField
 * @package common\widgets
 */
class RangeField extends Widget {
    /**
     * @var Model
     */
    public $model;
    /**
     * @var ActiveForm
     */
    public $form;
    /**
     * @var
     */
    public $attribute;
    /**
     * @var
     */
    public $attribute2;
    /**
     * @var
     */
    public $placeholder;
    /**
     * @var
     */
    public $placeholder2;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $form  = $this->form;
        $model = $this->model;

        echo $form->beginField($model, $this->attribute);
        echo $form->beginField($model, $this->attribute2);

        echo Html::activeLabel($model, $this->attribute);
        echo Html::beginTag('div', ['class' => 'input-group form-range']);
        echo Html::activeTextInput($this->model, $this->attribute, [
            'class'       => 'form-control',
            'placeholder' => $this->placeholder
        ]);
        echo Html::tag('span', '-', ['class' => 'input-group-addon']);
        echo Html::activeTextInput($this->model, $this->attribute2, [
            'class'       => 'form-control',
            'placeholder' => $this->placeholder2
        ]);
        echo Html::endTag('div');
        echo Html::error($model, $this->attribute, ['class' => 'help-block']);

        echo $form->endField();
        echo $form->endField();
    }
}
<?php

namespace common\widgets;

use yii\helpers\Html;

/**
 * Class ActiveField
 * @package common\components
 */
class ActiveField extends \yii\widgets\ActiveField {
    /**
     * @var string
     */
    public $template = "{label}\n{hint}\n{input}\n{error}";
    /**
     * @var array
     */
    public $hintOptions = [
        'class'       => 'hint-block',
        'data-toggle' => 'tooltip',
        'data-html'   => 'true',
    ];

    /**
     * @inheritdoc
     */
    public function hint($content, $options = [])
    {
        if ($content === false) {
            $this->parts['{hint}'] = '';
            return $this;
        }

        $options = array_merge($this->hintOptions, $options);
        $content = $content !== null ? $content : $this->model->getAttributeHint($this->attribute);
        $options['data-original-title'] = $content;

        if (empty($content)){
            $this->parts['{hint}'] = null;
        }else{
            $this->parts['{hint}'] = Html::a('<i class="fa fa-question-circle"></i>', '#', $options);
        }

        return $this;
    }
}
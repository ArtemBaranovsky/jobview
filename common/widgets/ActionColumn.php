<?php

namespace common\widgets;

use common\components\VarDumper;
use yii;
use yii\grid\Column;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * Class ActionColumn
 * @package modules\task\widgets
 */
class ActionColumn extends Column {
    /**
     * @inheritdoc
     */
    public $contentOptions = [
        'class' => 'text-center'
    ];
    /**
     * Первая кнопка
     * @var string
     */
    public $firstButton = 'view';
    /**
     * Скрытые кнопки
     * @var array
     */
    public $hiddenButtons = ['update', 'hr', 'delete'];
    /**
     * @var array
     */
    public $firstOptions = ['class' => 'btn btn-primary btn-xs btn-flat'];
    /**
     * @var array
     */
    public $hiddenOptions = [];
    /**
     * @var array
     */
    public $caretOptions = [];
    /**
     * @var bool
     */
    public $buttonOptions = [];
    /**
     * @var array
     */
    public $buttons = [];
    /**
     * @var callable
     */
    public $urlCreator;
    /**
     * @var
     */
    public $controller;
    /**
     * @var array
     */
    public $visibleButtons = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $first   = null;
        $hidden  = [];
        $buttons = array_merge($this->defaultButtons(), $this->buttons);

        if (
            empty($this->firstButton) === false && isset($buttons[$this->firstButton]) &&
            (isset($this->visibleButtons[$this->firstButton]) === false || $this->visibleButtons[$this->firstButton] !== false)
        ){
            $url   = $this->createUrl($this->firstButton, $model, $key, $index);
            $first = call_user_func($buttons[$this->firstButton], $url, $model, $key);
        }

        foreach ($this->hiddenButtons as $button){
            if (
                $button == 'hr' &&
                (isset($this->visibleButtons[$button]) === false || $this->visibleButtons[$button] !== false)
            ){
                $hidden[] = '<li class="divider"></li>';
            }elseif (
                isset($buttons[$button]) &&
                (isset($this->visibleButtons[$button]) === false || $this->visibleButtons[$button] !== false)
            ){
                $url      = $this->createUrl($button, $model, $key, $index);
                $hidden[] = '<li>' . call_user_func($buttons[$button], $url, $model, $key) . '</li>';
            }
        }

        $caretOptions = $this->caretOptions;

        if (count($caretOptions) === 0){
            preg_match('/class="(.*?)"/ui', $first, $caretClass);
            if (isset($caretClass[1])){
                $caretOptions['class'] = $caretClass[1];
            }
        }

        if (isset($caretOptions['data']['toggle']) === false){
            $caretOptions['data'] = ['toggle' => 'dropdown'];
        }

        return
            '<div class="btn-group text-center">' .
                $first .
                (count($hidden) ? Html::button('<span class="caret"></span>', $caretOptions) : null) .
                (count($hidden) ? Html::ul($hidden, ['class' => 'dropdown-menu', 'encode' => false]) : null) .
            '</div>';
    }

    /**
     * @return array
     */
    protected function defaultButtons()
    {
        return [
            'up' => function ($url, $model, $key){
                $text    = Yii::t('app', 'BTN_UP');
                $options = $this->firstButton == 'up' ? $this->firstOptions : $this->hiddenOptions;

                if (isset($this->buttonOptions['up'])){
                    $options = array_merge($options, call_user_func($this->buttonOptions['up'], $model));

                    if (isset($options['label'])){
                        $text = $options['label'];
                        unset($options['label']);
                    }
                }

                return Html::a('<i class="fa fa-arrow-up"></i>&nbsp;' . $text, $url, $options);
            },
            'down' => function ($url, $model, $key){
                $text    = Yii::t('app', 'BTN_DOWN');
                $options = $this->firstButton == 'down' ? $this->firstOptions : $this->hiddenOptions;

                if (isset($this->buttonOptions['down'])){
                    $options = array_merge($options, call_user_func($this->buttonOptions['down'], $model));

                    if (isset($options['label'])){
                        $text = $options['label'];
                        unset($options['label']);
                    }
                }

                return Html::a('<i class="fa fa-arrow-down"></i>&nbsp;' . $text, $url, $options);
            },
            'view' => function ($url, $model, $key){
                $text    = Yii::t('app', 'BTN_VIEW');
                $options = $this->firstButton == 'view' ? $this->firstOptions : $this->hiddenOptions;

                if (isset($this->buttonOptions['view'])){
                    $options = array_merge($options, call_user_func($this->buttonOptions['view'], $model));

                    if (isset($options['label'])){
                        $text = $options['label'];
                        unset($options['label']);
                    }
                }

                return Html::a('<i class="fa fa-eye"></i>&nbsp;' . $text, $url, $options);
            },
            'update' => function ($url, $model, $key){
                $text    = Yii::t('app', 'BTN_UPDATE');
                $options = $this->firstButton == 'update' ? $this->firstOptions : $this->hiddenOptions;

                if (isset($this->buttonOptions['update'])){
                    $options = array_merge($options, call_user_func($this->buttonOptions['update'], $model));

                    if (isset($options['label'])){
                        $text = $options['label'];
                        unset($options['label']);
                    }
                }

                return Html::a('<i class="fa fa-edit"></i>&nbsp;' . $text, $url, $options);
            },
            'delete' => function ($url, $model, $key){
                $text    = Yii::t('app', 'BTN_DELETE');
                $options = $this->firstButton == 'delete' ? $this->firstOptions : $this->hiddenOptions;
                $options['data'] = ['method' => 'post'];

                if (isset($this->buttonOptions['delete'])){
                    $options = array_merge($options, call_user_func($this->buttonOptions['delete'], $model));

                    if (isset($options['label'])){
                        $text = $options['label'];
                        unset($options['label']);
                    }
                }

                return Html::a('<i class="fa fa-times"></i>&nbsp;' . $text, $url, $options);
            },
        ];
    }

    /**
     * @param $action
     * @param $model
     * @param $key
     * @param $index
     * @return mixed
     */
    protected function createUrl($action, $model, $key, $index)
    {
        if (is_callable($this->urlCreator)) {
            return call_user_func($this->urlCreator, $action, $model, $key, $index);
        } else {
            $params = is_array($key) ? $key : ['id' => (string) $key];
            $params[0] = $this->controller ? $this->controller . '/' . $action : $action;

            return Url::toRoute($params);
        }
    }
}
<?php

namespace common\components;

class VarDumper extends \yii\helpers\VarDumper {
    /**
     * @inheritdoc
     */
    public static function dump($var, $depth = 10, $highlight = true)
    {
        parent::dump($var, $depth, $highlight);
    }
}
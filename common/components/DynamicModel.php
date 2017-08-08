<?php

namespace common\components;

/**
 * Class DynamicModel
 * @package common\components
 */
class DynamicModel extends \yii\base\DynamicModel {
    /**
     * @var array
     */
    private $_attributeLabels = [];

    /**
     * @param array $attributes
     */
    public function setAttributeLabels($attributes = [])
    {
        $this->_attributeLabels = $attributes;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return $this->_attributeLabels;
    }
}
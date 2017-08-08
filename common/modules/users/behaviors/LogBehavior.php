<?php

namespace modules\users\behaviors;

use common\components\VarDumper;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use modules\users\models\BaseLog;

/**
 * Class LogBehavior
 * @package modules\users\behaviors
 */
class LogBehavior extends Behavior
{
    /**
     * Insert type
     */
    const TYPE_INSERT = 'insert';
    /**
     * Update type
     */
    const TYPE_UPDATE = 'update';
    /**
     * Delete type
     */
    const TYPE_DELETE = 'delete';
    /**
     * @var array
     */
    public $types = [];
    /**
     * @var array
     */
    public $onlyAttributes = [];
    /**
     * Old attributes
     * @var array
     */
    protected $old = [];
    /**
     * New attributes
     * @var array
     */
    protected $new = [];

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT   => 'beforeEvent',
            ActiveRecord::EVENT_BEFORE_DELETE   => 'beforeEvent',

            ActiveRecord::EVENT_AFTER_INSERT    => 'afterInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE   => 'beforeUpdate',
            ActiveRecord::EVENT_AFTER_DELETE    => 'afterDelete',
        ];
    }

    /**
     * Event before
     */
    public function beforeEvent()
    {
        /* @var $owner ActiveRecord */
        $owner = $this->owner;
        $this->old = $owner->oldAttributes;
    }

    /**
     * Insert event
     * @return bool
     */
    public function afterInsert()
    {
        /* @var $owner ActiveRecord */
        $owner = $this->owner;
        $type = self::TYPE_INSERT;

        $this->old = [];
        $this->new = $owner->attributes;

        if (isset($this->types[$type])){
            $value   = $this->types[$type];
            $value   = is_callable($value) ? call_user_func($value, $this->owner) : $value;
            $message = ArrayHelper::remove($value, 0);
            $params  = $value;

            $this->saveLog($type, $message, $params);
        }

        return true;
    }

    /**
     * Update event
     * @return bool
     */
    public function beforeUpdate()
    {
        /* @var $owner ActiveRecord */
        $owner = $this->owner;
        $type = self::TYPE_UPDATE;

        $this->old = $owner->oldAttributes;
        $this->new = $owner->attributes;

        if (isset($this->types[$type])){
            $value   = $this->types[$type];
            $value   = is_callable($value) ? call_user_func($value, $this->owner) : $value;
            $message = ArrayHelper::remove($value, 0);
            $params  = $value;

            $this->saveLog($type, $message, $params);
        }

        return true;
    }

    /**
     * Delete event
     * @return bool
     */
    public function afterDelete()
    {
        $type = self::TYPE_DELETE;
        $this->new = [];

        if (isset($this->types[$type])){
            $value   = $this->types[$type];
            $value   = is_callable($value) ? call_user_func($value, $this->owner) : $value;
            $message = ArrayHelper::remove($value, 0);
            $params  = $value;

            $this->saveLog($type, $message, $params);
        }

        return true;
    }

    /**
     * @param $type
     * @param $message
     * @param $params
     */
    protected function saveLog($type, $message, $params)
    {
        BaseLog::create($type, $message, $params, $this->old, $this->new, $this->onlyAttributes);
    }
}
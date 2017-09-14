<?php

namespace modules\users\models;

use yii;
use modules\users\Module;

/**
 * This is the model class for table "{{%app_session}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $ip
 * @property string $ua
 * @property integer $expire
 * @property string $data
 */
class BaseSession extends yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%app_session}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => Module::t('main', 'ATTR_SESSION_ID'),
            'user_id'     => Module::t('main', 'ATTR_SESSION_USER_ID'),
            'ip'          => Module::t('main', 'ATTR_SESSION_IP'),
            'ua'          => Module::t('main', 'ATTR_SESSION_UA'),
            'last_action' => Module::t('main', 'ATTR_SESSION_LAST_ACTION'),
            'expire'      => Module::t('main', 'ATTR_SESSION_EXPIRE'),
            'data'        => Module::t('main', 'ATTR_SESSION_DATA'),
        ];
    }
}
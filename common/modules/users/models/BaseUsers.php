<?php

namespace modules\users\models;

use yii;
use modules\users\Module;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%users}}".
 *
 * @property integer $id
 * @property string $auth_key
 * @property string $email
 * @property string $password
 * @property string $name
 * @property string $phone
 * @property string $role
 * @property integer $error_count_auth
 * @property integer $time_registration
 * @property integer $time_last_visit
 * @property string $activate_code
 * @property integer $activate_time
 * @property string $recovery_code
 * @property integer $recovery_time
 * @property string $subscribe_news
 * @property string $subscribe_notifications
 * @property string $utm_source
 * @property string $utm_medium
 * @property string $utm_campaign
 * @property string $utm_term
 * @property string $utm_content
 * @property integer $ban_exists
 * @property string $ban_description
 * @property string $ip
 * @property string $ua
 */

class BaseUsers extends yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * Капча
     * @var
     */
    public $captcha;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'password'], 'required'],
            [['email'], 'email',
                'message' => Module::t('main', 'ERROR_INCORRECT_EMAIL')
            ],
            [['password'], 'string', 'min' => 6, 'max' => 255,
                'message'    => Module::t('main', 'ERROR_INCORRECT_PASSWORD'),
                'tooShort'   => Module::t('main', 'ERROR_SHORT_PASSWORD'),
                'tooLong'    => Module::t('main', 'ERROR_LONG_PASSWORD'),
            ],
            [['name'], 'string', 'max' => 50,
                'message'    => Module::t('main', 'ERROR_INCORRECT_NAME'),
                'tooLong'    => Module::t('main', 'ERROR_LONG_NAME'),
            ],
            [['email'], 'unique',
                'message'    => Module::t('main', 'ERROR_EXISTS_EMAIL'),
            ],
            //[['role'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                        => Module::t('main', 'ATTR_ID'),
            'email'                     => Module::t('main', 'ATTR_EMAIL'),
            'password'                  => Module::t('main', 'ATTR_PASSWORD'),
            'name'                      => Module::t('main', 'ATTR_NAME'),
            'phone'                     => Module::t('main', 'ATTR_PHONE'),
            'role'                      => Module::t('main', 'ATTR_ROLE'),
            'avatar'                    => Module::t('main', 'ATTR_AVATAR'),
            'time_registration'         => Module::t('main', 'ATTR_TIME_REGISTRATION'),
            'time_last_visit'           => Module::t('main', 'ATTR_TIME_LAST_VISIT'),
            'activate_time'             => Module::t('main', 'ATTR_ACTIVATE_TIME'),
            'subscribe_news'            => Module::t('main', 'ATTR_SUBSCRIBE_NEWS'),
            'subscribe_notifications'   => Module::t('main', 'ATTR_SUBSCRIBE_NOTIFICATIONS'),
            'captcha'                   => Module::t('main', 'ATTR_CAPTCHA'),
            'utm_source'                => Module::t('main', 'ATTR_UTM_SOURCE'),
            'utm_medium'                => Module::t('main', 'ATTR_UTM_MEDIUM'),
            'utm_campaign'              => Module::t('main', 'ATTR_UTM_CAMPAIGN'),
            'utm_term'                  => Module::t('main', 'ATTR_UTM_TERM'),
            'utm_content'               => Module::t('main', 'ATTR_UTM_CONTENT'),
            'ban_exists'                => Module::t('main', 'ATTR_BAN_EXISTS'),
            'ban_description'           => Module::t('main', 'ATTR_BAN_DESCRIPTION'),
            'ip'                        => Module::t('main', 'ATTR_IP'),
            'ua'                        => Module::t('main', 'ATTR_UA'),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['auth_key' => $token]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        /**
         * Генерируем код отписки от рассылки новостей
         */
        if ($this->isNewRecord){
            $this->generateSubscribeNewsCode();
        }

        /**
         * Генерируем код отписки от рассылки оповещений
         */
        if ($this->isNewRecord){
            $this->generateSubscribeNotificationsCode();
        }

        return parent::beforeSave($insert);
    }

    /**
     * Активация учетной записи
     */
    public function activateAccount()
    {
        $this->activate_code = null;
        $this->activate_time = time();
        $this->detachBehavior('log');
        $this->save(0);
    }

    /**
     * Обновляем время последнего посещения, User Agent и IP адрес.
     */
    public function updateDataAction()
    {
        $this->updateAttributes([
            'time_last_visit' => time(),
            'ip' => Yii::$app->request->userIP,
            'ua' => Yii::$app->request->userAgent,
        ]);
    }

    /**
     * Генерируем код отписки от рассылки новостей
     */
    protected function generateSubscribeNewsCode()
    {
        $this->subscribe_news = md5(
            $this->id .
            '|' .
            microtime(true) .
            Yii::$app->security->generateRandomString()
        );
    }

    /**
     * Генерируем код отписки от рассылки оповещений
     */
    protected function generateSubscribeNotificationsCode()
    {
        $this->subscribe_notifications = md5(
            $this->id .
            '|' .
            microtime(true) .
            Yii::$app->security->generateRandomString()
        );
    }
}
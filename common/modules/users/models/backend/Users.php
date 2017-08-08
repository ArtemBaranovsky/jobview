<?php

namespace modules\users\models\backend;

use yii;
use modules\users\Module;
use modules\users\models\BaseUsers;
use modules\users\behaviors\LogBehavior;

/**
 * Class Users
 * @package modules\users\models\backend
 */
class Users extends BaseUsers {
    /**
     * Сценарий создания учетной записи
     */
    const SCENARIO_CREATE = 'create';
    /**
     * Сценарий редактирования учетной записи
     */
    const SCENARIO_UPDATE = 'update';
    /**
     * @var
     */
    public $password_new;
    /**
     * @var
     */
    public $password_repeat;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'name'], 'required'],
            [['name'], 'string', 'max' => 50,
                'message'    => Module::t('main', 'ERROR_INCORRECT_NAME'),
                'tooLong'    => Module::t('main', 'ERROR_LONG_NAME'),
            ],

            [['email'], 'email',
                'message' => Module::t('main', 'ERROR_INCORRECT_EMAIL')
            ],
            [['email'], 'unique',
                'message'    => Module::t('main', 'ERROR_EXISTS_EMAIL'),
            ],

            [['password_new'],
                'required',
                'when' => function ($model){
                    /* @var $model self */
                    return $this->isNewRecord;
                },
                'whenClient' => 'function (attribute, value) {
                    return ' . ($this->isNewRecord ? 'true' : 'false') . ';
                }'
            ],
            [['password_new'], 'string', 'min' => 6, 'max' => 255,
                'message'    => Module::t('main', 'ERROR_INCORRECT_PASSWORD'),
                'tooShort'   => Module::t('main', 'ERROR_SHORT_PASSWORD'),
                'tooLong'    => Module::t('main', 'ERROR_LONG_PASSWORD'),
            ],
            ['password_repeat',
                'compare',
                'skipOnEmpty' => false,
                'compareAttribute' => 'password_new',
                'message' => Module::t('main', 'ERROR_COMPARE_PASSWORD'),
                'when' => function ($model){
                    /* @var $model self */
                    return empty($model->password_new) === false;
                },
                'whenClient' => 'function (attribute, value) {
                    return $("#users-password_new").val() != "";
                }'
            ],

            [['ban_exists'], 'boolean'],

            [['ban_description'],
                'string',
                'max' => 1000,
            ],

            [['ban_description'],
                'required',
                'when' => function ($model){
                    /* @var $model self */
                    return $model->ban_exists == 1;
                },
                'whenClient' => 'function (attribute, value) {
                    return $("#users-ban_exists").prop("checked") == true;
                }'
            ]
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'email'              => Module::t('main', 'ATTR_EMAIL'),
            'name'               => Module::t('main', 'ATTR_NAME'),
            'password_new'       => Module::t('main', 'ATTR_PASSWORD_NEW'),
            'password_repeat'    => Module::t('main', 'ATTR_PASSWORD_REPEAT'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'log' => [
                'class' => LogBehavior::className(),
                'onlyAttributes' => [
                    'name',
                    'email',
                    'password',
                    'ban_exists',
                    'ban_description'
                ],
                'types' => [
                    'update' => function ($model){
                        return ['UPDATE_USER_{id}', 'id' => $model->id];
                    },
                    'insert' => function ($model){
                        return ['CREATE_USER_{id}', 'id' => $model->id];
                    },
                    'delete' => function ($model){
                        return ['DELETE_USER_{id}', 'id' => $model->id];
                    },
                ]
            ]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord){
            $this->time_last_visit   = time();
            $this->time_registration = time();
            $this->activate_time     = time();
        }

        if (empty($this->password_new) === false){
            $this->password = Yii::$app->security->generatePasswordHash($this->password_new);
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE  => [
                'email',
                'name',
                'password_new',
                'password_repeat',
            ],
            self::SCENARIO_UPDATE  => [
                'email',
                'name',
                'password_new',
                'password_repeat',
                'ban_exists',
                'ban_description'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function activateAccount()
    {
        $old = $this->oldAttributes;

        parent::activateAccount();

        Log::create(
            Log::TYPE_UPDATE,
            'ACTIVATE_ACCOUNT',
            [
                'id' => $this->id
            ],
            $old,
            $this->attributes,
            [],
            $this->id
        );
    }
}
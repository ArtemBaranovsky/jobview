<?php

namespace modules\users\models\frontend\forms;

use yii;
use yii\base\Model;
use modules\users\Module;
use modules\users\models\frontend\Users;

/**
 * Class Login
 * @package modules\users\models\frontend\forms
 */
class Login extends Model {
    /**
     * Email.
     * @var
     */
    public $email;
    /**
     * Пароль.
     * @var
     */
    public $password;
    /**
     * @var Users
     */
    protected $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            [['email', 'password'], 'string'],

            [['email'], 'validateEmail'],
            [['password'], 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'      => Module::t('main', 'ATTR_EMAIL'),
            'password'   => Module::t('main', 'ATTR_PASSWORD'),
        ];
    }

    /**
     * Проверяем введенный email на корректность.
     * @param $attribute
     * @param array $params
     */
    public function validateEmail($attribute, $params = [])
    {
        $user = Users::findOne(['email' => $this->email]);

        if ($user === null){
            $this->addError($attribute, Module::t('main', 'ERROR_INCORRECT_EMAIL'));
        }else{
            $this->_user = $user;
        }
    }

    /**
     * Проверяем введенный пароль на корректность.
     * @param $attribute
     * @param array $params
     */
    public function validatePassword($attribute, $params = [])
    {
        if ($this->_user && Yii::$app->security->validatePassword($this->password, $this->_user->password) === false){
            $this->addError($attribute, Module::t('main', 'ERROR_INCORRECT_PASSWORD'));
        }
    }

    /**
     * Авторизуем пользователя.
     * @return bool
     */
    public function login()
    {
        if ($this->validate()){
            return Yii::$app->user->login($this->_user, Yii::$app->params['loginDuration']);
        }

        return false;
    }
}
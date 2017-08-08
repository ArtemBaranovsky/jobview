<?php

namespace modules\users\models\frontend\forms;

use yii;
use yii\base\Model;
use yii\helpers\Url;
use modules\users\Module;
use common\components\SendMail;
use modules\users\models\frontend\Log;
use modules\users\models\frontend\Users;
use himiklab\yii2\recaptcha\ReCaptchaValidator;

/**
 * Class PasswordRecovery
 * @package modules\users\models\frontend\forms
 */
class PasswordRecovery extends Model {
    /**
     * Сценарий запроса на восстановление пароля.
     */
    const SCENARIO_REQUEST = 'request';
    /**
     * Сценарий установки нового пароля.
     */
    const SCENARIO_SET = 'set';
    /**
     * Email.
     * @var
     */
    public $email;
    /**
     * @var
     */
    public $password_new;
    /**
     * @var
     */
    public $password_repeat;
    /**
     * @var Users
     */
    public $user;
    /**
     * Капча
     * @var
     */
    public $captcha;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'email',
                'message' => Module::t('main', 'ERROR_INCORRECT_EMAIL')
            ],
            [['email'], 'validateEmail'],

            [['password_new', 'password_repeat'], 'required'],
            [['password_new'], 'string', 'min' => 6, 'max' => 255,
                'message'    => Module::t('main', 'ERROR_INCORRECT_PASSWORD'),
                'tooShort'   => Module::t('main', 'ERROR_SHORT_PASSWORD'),
                'tooLong'    => Module::t('main', 'ERROR_LONG_PASSWORD'),
            ],
            ['password_repeat',
                'compare',
                'compareAttribute' => 'password_new',
                'message' => Module::t('main', 'ERROR_COMPARE_PASSWORD'),
            ],
            // Captcha
            //['captcha', ReCaptchaValidator::className(), 'secret' => Yii::$app->reCaptcha->secret]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'           => Module::t('main', 'ATTR_EMAIL'),
            'password_new'    => Module::t('main', 'ATTR_PASSWORD_NEW'),
            'password_repeat' => Module::t('main', 'ATTR_PASSWORD_REPEAT'),
            'captcha'         => Module::t('main', 'ATTR_CAPTCHA'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_REQUEST   => ['email', 'captcha'],
            self::SCENARIO_SET       => ['password_new', 'password_repeat'],
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
            $this->user = $user;
        }
    }

    /**
     * @return bool
     */
    public function sendMail()
    {
        if ($this->validate() === false){
            return false;
        }

        $code         = Yii::$app->security->generateRandomString();
        $activateUrl  = Url::to(['/users/default/set-password', 'code' => $code], true);

        /**
         * Html версия письма
         */
        $html = Yii::$app->view->renderFile('@common/mail/password-recovery-html.php', [
            'activateUrl' => $activateUrl
        ]);
        /**
         * Текстовая версия письма
         */
        $text = Yii::$app->view->renderFile('@common/mail/password-recovery-text.php', [
            'activateUrl' => $activateUrl
        ]);

        /**
         * Отправляем письмо
         */
        $mail = new SendMail();
        $mail->to      = $this->email;
        $mail->subject = Module::t('main', 'TITLE_PASSWORD_RECOVERY');
        $mail->html    = $html;
        $mail->text    = $text;
        $mail->send();

        /**
         * Сохраняем данные
         */
        $user = $this->user;
        $user->recovery_code = $code;
        $user->recovery_time = time();
        $user->detachBehavior('log');
        return $user->save(false);
    }

    /**
     * @return bool
     */
    public function setPassword()
    {
        if ($this->validate() === false){
            return false;
        }

        $user = $this->user;
        $user->detachBehavior('log');

        $user->recovery_code = null;
        $user->recovery_time = 0;
        $user->password      = Yii::$app->security->generatePasswordHash($this->password_new);

        $old = $user->oldAttributes;

        if ($user->save(false)){
            Log::create(
                Log::TYPE_UPDATE,
                'RECOVERY_PASSWORD',
                [],
                $old,
                $user->attributes,
                ['password'],
                $user->id
            );

            return true;
        }

        return false;
    }
}
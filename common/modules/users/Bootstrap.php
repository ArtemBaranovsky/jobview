<?php

namespace modules\users;

use yii;
use yii\helpers\Url;
use yii\base\BootstrapInterface;
use modules\users\models\frontend\Users;

/**
 * Class Bootstrap
 * @package modules\users
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        (new Module('users'))->init();

        if ($app->id === 'frontend'){
            $rules['ban']                                    = 'users/default/ban';
            $rules['user']                                   = 'users/default/index';
            $rules['login']                                  = 'users/default/login';
            $rules['logout']                                 = 'users/default/logout';
            $rules['registration']                           = 'users/default/registration';
            $rules['password-recovery']                      = 'users/default/password-recovery';
            $rules['user/activate/<code:(.*)>']              = 'users/default/activate';
            $rules['password-set/<code:(.*)>']               = 'users/default/set-password';
            $rules['unsubscribe-news/<code:(.*)>']           = 'users/default/unsubscribe-news';
            $rules['unsubscribe-notifications/<code:(.*)>']  = 'users/default/unsubscribe-notifications';

            $this->updateUserData();
            $app->urlManager->addRules($rules, false);

            if (
                Yii::$app->user->isGuest === false &&
                Yii::$app->user->identity->ban_exists == 1 &&
                in_array(Yii::$app->request->pathInfo, ['ban', 'logout', 'debug/default/toolbar']) === false
            ){
                header('Location: ' . Url::to(['/users/default/ban']));
                exit;
            }
        }
    }

    /**
     * Обновляем данные пользователя
     */
    protected function updateUserData()
    {
        if (Yii::$app->user->isGuest){
            (new Users())->hundlerUtmData();
        }else{
            Yii::$app->user->identity->updateDataAction();
        }
    }
}
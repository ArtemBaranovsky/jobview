<?php

namespace modules\users\controllers\frontend;

use yii;
use yii\web\Cookie;
use yii\web\Controller;
use modules\users\Module;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use modules\users\models\frontend\Log;
use modules\users\models\frontend\Users;
use modules\users\models\frontend\forms\Login;
use modules\users\models\frontend\forms\PasswordRecovery;

/**
 * Class DefaultController
 * @package modules\users\controllers\frontend
 */
class DefaultController extends Controller{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return string|yii\web\Response
     */
    public function actionIndex()
    {
        /* @var $model Users */
        $model = Yii::$app->user->identity;
        $model->scenario = Users::SCENARIO_PROFILE;

        if ($model->load(Yii::$app->request->post()) && $model->save()){
            if ($model->change_email){
                Yii::$app->session->setFlash('warning', Yii::t('app', 'MESSAGE_GUIDE_ACTIVATE_EMAIL_{email}', [
                    'email' => $model->email
                ]));
            }else{
                Yii::$app->session->setFlash('success', Yii::t('app', 'SUCCESS_SAVE_DATA'));
            }

            return $this->redirect(['index']);
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }

    /**
     * @return string
     */
    public function actionLogin()
    {
        $model = new Login();
        $this->layout = 'empty';

        if ($model->load(Yii::$app->request->post()) && $model->login()){
            return $this->redirect(['/']);
        }

        return $this->render('login', ['model' => $model]);
    }

    /**
     * @return yii\web\Response
     */
    public function actionLogout()
    {
        $user_id = Yii::$app->user->id;

        /**
         * Немного говнокода))
         */
        if (isset($_COOKIE['auth_key'])){
            $model = Users::findIdentityByAccessToken($_COOKIE['auth_key']);

            if ($model){
                Yii::$app->response->cookies->remove(new Cookie([
                    'name' => 'auth_key'
                ]));
                $model->updateAttributes(['auth_key' => null]);

                Log::create(Log::TYPE_AUTH, 'USER_AUTH_LOGOUT_{id}', [
                    'id' => Yii::$app->user->id
                ], [], [], [], $model->id);

                Yii::$app->user->switchIdentity($model);
                return $this->redirect(['/backend/users/default/view', 'id' => $user_id]);
            }
        }

        Yii::$app->user->logout();
        return $this->redirect(['/']);
    }

    /**
     * @return string
     */
    public function actionRegistration()
    {
        $this->layout = 'empty';

        $model = new Users(['scenario' => Users::SCENARIO_REGISTRATION]);

        if ($model->load(Yii::$app->request->post()) && $model->save()){
            /**
             * Отправляем цель в метрику
             */
            Yii::$app->session->setFlash('goal_registration');

            Yii::$app->user->login($model, Yii::$app->params['loginDuration']);
            Yii::$app->session->setFlash('success', Module::t('main', 'MESSAGE_REGISTRATION_SUCCESS'));

            return $this->redirect(['/main/default/index']);
        }

        return $this->render('registration', ['model' => $model]);
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionBan()
    {
        if (Yii::$app->user->identity->ban_exists != 1){
            throw new NotFoundHttpException(Yii::t('app', 'ERROR_404'));
        }

        $this->layout = 'empty';
        return $this->render('ban', [
            'model' => Yii::$app->user->identity
        ]);
    }

    /**
     * @param $code
     * @return yii\web\Response
     */
    public function actionActivate($code)
    {
        $model = $this->findActivateModel($code);
        $model->activateAccount();

        /**
         * Отправляем цель в метрику
         */
        Yii::$app->session->setFlash('goal_activate_account');
        Yii::$app->session->setFlash('success', Module::t('main', 'MESSAGE_ACTIVATE_ACCOUNT_SUCCESS'));

        return $this->redirect(['/main/default/index']);
    }

    /**
     * @return string
     */
    public function actionPasswordRecovery()
    {
        $this->layout = 'empty';
        $model = new PasswordRecovery([
            'scenario' => PasswordRecovery::SCENARIO_REQUEST
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->sendMail()){
            return $this->render('password-recovery-send', [
                'model' => $model
            ]);
        }

        return $this->render('password-recovery', [
            'model' => $model
        ]);
    }

    /**
     * @param $code
     * @return string|yii\web\Response
     */
    public function actionSetPassword($code)
    {
        $this->layout = 'empty';
        $model = $this->findRecoveryModel($code);

        if ($model->load(Yii::$app->request->post()) && $model->setPassword()){
            Yii::$app->session->setFlash('success', Module::t('main', 'MESSAGE_RECOVERY_PASSWORD_SUCCESS'));
            return $this->redirect(['login']);
        }

        return $this->render('set-password', [
            'model' => $model
        ]);
    }

    /**
     * @param $code
     * @return string
     */
    public function actionUnsubscribeNews($code)
    {
        if (Yii::$app->user->isGuest){
            $view         = 'unsubscribe-news-guest';
            $this->layout = 'empty';
        }else{
            $view = 'unsubscribe-news';
        }

        $model = $this->findSubscribeNews($code);

        if (Yii::$app->request->isPost){
            $model->unsubscribeNews();
            Yii::$app->session->setFlash('success', Module::t('main', 'MESSAGE_UNSUBSCRIBE_NEWS_CONFIRM_SUCCESS'));

            return $this->redirect(['/']);
        }

        return $this->render($view, [
            'model' => $model
        ]);
    }

    /**
     * @param $code
     * @return string
     */
    public function actionUnsubscribeNotifications($code)
    {
        if (Yii::$app->user->isGuest){
            $view         = 'unsubscribe-notifications-guest';
            $this->layout = 'empty';
        }else{
            $view = 'unsubscribe-notifications';
        }

        $model = $this->findSubscribeNotifications($code);

        if (Yii::$app->request->isPost){
            $model->unsubscribeNotifications();
            Yii::$app->session->setFlash('success', Module::t('main', 'MESSAGE_UNSUBSCRIBE_NOTIFICATIONS_CONFIRM_SUCCESS'));

            return $this->redirect(['/']);
        }

        return $this->render($view, [
            'model' => $model
        ]);
    }

    /**
     * @param $code
     * @return PasswordRecovery
     * @throws ForbiddenHttpException
     */
    protected function findRecoveryModel($code)
    {
        $user = Users::findOne(['recovery_code' => $code]);

        if ($user === null){
            throw new ForbiddenHttpException(Module::t('main', 'MESSAGE_RECOVERY_PASSWORD_CODE_NOT_FOUND'));
        }elseif ($user->recovery_time + Yii::$app->params['expireRecovery'] <= time()){
            throw new ForbiddenHttpException(Module::t('main', 'MESSAGE_RECOVERY_PASSWORD_CODE_EXPIRE'));
        }

        $model = new PasswordRecovery([
            'user' => $user,
            'scenario' => PasswordRecovery::SCENARIO_SET
        ]);

        return $model;
    }

    /**
     * @param $code
     * @return Users
     * @throws ForbiddenHttpException
     */
    protected function findActivateModel($code)
    {
        $model = Users::findOne(['activate_code' => $code]);

        if ($model === null){
            throw new ForbiddenHttpException(Module::t('main', 'MESSAGE_ACTIVATE_ACCOUNT_CODE_NOT_FOUND'));
        }

        return $model;
    }

    /**
     * @param $code
     * @return Users
     * @throws ForbiddenHttpException
     */
    protected function findSubscribeNews($code)
    {
        $model = Users::findOne(['subscribe_news' => $code]);

        if ($model === null){
            throw new ForbiddenHttpException(Module::t('main', 'MESSAGE_SUBSCRIBE_NEWS_CODE_NOT_FOUND'));
        }

        return $model;
    }

    /**
     * @param $code
     * @return Users
     * @throws ForbiddenHttpException
     */
    protected function findSubscribeNotifications($code)
    {
        $model = Users::findOne(['subscribe_notifications' => $code]);

        if ($model === null){
            throw new ForbiddenHttpException(Module::t('main', 'MESSAGE_SUBSCRIBE_NOTIFICATIONS_CODE_NOT_FOUND'));
        }

        return $model;
    }
}
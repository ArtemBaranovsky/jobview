<?php

namespace modules\users\controllers\backend;

use yii;
use yii\web\Cookie;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use modules\users\models\backend\Log;
use modules\users\models\backend\Users;
use modules\users\models\backend\forms\Login;
use modules\users\models\backend\search\Users as UsersSearch;

/**
 * Class DefaultController
 * @package modules\users\controllers\backend
 */
class DefaultController extends Controller{
    /**
     * @var int
     */
    protected $pageSize = 10;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'auth'           => ['POST'],
                    'delete'         => ['POST'],
                    'activate-email' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'       => true,
                        'actions'     => ['login'],
                        'roles'       => ['?'],
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['index', 'view'],
                        'roles'   => ['users-view'],
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['create', 'update', 'delete', 'activate-email'],
                        'roles'   => ['users-crud'],
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['auth'],
                        'roles'   => ['users-auth'],
                    ],
                ],
            ]
        ];
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
    public function actionIndex()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string|yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Users([
            'scenario' => Users::SCENARIO_CREATE
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Пользователь успешно добавлен!');
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string|yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = Users::SCENARIO_UPDATE;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Данные пользователя успешно изменены!');
            return Yii::$app->request->get('ref') ?
                $this->redirect(['view', 'id' => $model->id]) : $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->id == $id){
            throw new BadRequestHttpException('Вы не можете удалить сами себя!');
        }

        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Пользователь успешно удален!');

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model'   => $model,
            'log'     => $this->listLog($id)
        ]);
    }

    /**
     * @param $id
     * @return yii\web\Response
     */
    public function actionAuth($id)
    {
        $model    = $this->findModel($id);
        $duration = Yii::$app->params['loginDuration'];

        /**
         * Немного говнокода))
         */
        if ($id != Yii::$app->user->id){
            $authKey = Yii::$app->security->generateRandomString();
            setcookie('auth_key', $authKey, time() + $duration, '/');
            Yii::$app->user->identity->updateAttributes([
                'auth_key' => $authKey
            ]);
            Log::create(Log::TYPE_AUTH, 'USER_AUTH_LOGIN_{id}', [
                'id' => $model->id
            ]);
            Yii::$app->user->switchIdentity($model, Yii::$app->params['loginDuration']);
        }

        return $this->redirect('/');
    }

    /**
     * @param $id
     * @return yii\web\Response
     */
    public function actionActivateEmail($id)
    {
        $model = $this->findModel($id);

        if ($model->activate_time < 1){
            $model->activateAccount();
            Yii::$app->session->setFlash('success', 'Учетная запись успешно активирована!');
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * @param $id
     * @return Users
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = Users::findOne($id);

        if ($model === null){
            throw new NotFoundHttpException(Yii::t('app', 'ERROR_404'));
        }

        return $model;
    }

    /**
     * @param $id
     * @return ActiveDataProvider
     */
    protected function listLog($id)
    {
        return new ActiveDataProvider([
            'query' => Log::find()->where(['user_id' => $id]),
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ],
            'pagination' => [
                'pageParam' => 'page-log',
                'defaultPageSize' => $this->pageSize
            ]
        ]);
    }
}
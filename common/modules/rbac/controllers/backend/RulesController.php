<?php

namespace modules\rbac\controllers\backend;

use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use modules\rbac\models\RulesForm;
use yii\web\ForbiddenHttpException;
use modules\users\models\backend\Log;

/**
 * Class RulesController
 * @package modules\rbac\controllers\frontend
 */
class RulesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'delete-multiple' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['rbac']
                    ]
                ],
            ]
        ];
    }

    /**
     * Вывод всех правил
     * @return mixed
     */
    public function actionIndex()
    {
        $data = Yii::$app->authManager->getRules();
        $dataProvider = new ArrayDataProvider([
            'models' => $data,
            'totalCount' => count($data)
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Создание правила
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RulesForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Удаление правила
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException если нет такого правила
     */
    public function actionDelete($id)
    {
        $auth = Yii::$app->authManager;
        if (($rule = $auth->getRule($id)) != null) {
            $auth->remove($rule);
            /**
             * Лень было исправлять этот УГкод
             */
            Log::create(Log::TYPE_INSERT, 'DELETE_RBAC_RULE_{alias}', ['alias' => $id],
                ['namespace' => $id],
                ['namespace' => $id],
                ['namespace']
            );
        } else {
            throw new NotFoundHttpException('Правило не найдена');
        }

        return $this->redirect(['index']);
    }

    /**
     * Массовое удаление правил
     * @return mixed
     */
    public function actionDeleteMultiple()
    {
        $auth = Yii::$app->authManager;
        $selection = Yii::$app->request->post('selection', []);

        foreach ($selection as $rule) {
            if (($rule = $auth->getRule($rule)) != null) {
                $auth->remove($rule);
                /**
                 * Лень было исправлять этот УГкод
                 */
                Log::create(Log::TYPE_INSERT, 'DELETE_RBAC_RULE_{alias}', ['alias' => $rule],
                    ['namespace' => $rule],
                    ['namespace' => $rule],
                    ['namespace']
                );
            }
        }

        return $this->redirect(['index']);
    }
}

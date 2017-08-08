<?php

namespace modules\rbac\controllers\backend;

use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use modules\rbac\models\PermissionsForm;

/**
 * Class DefaultController
 * @package modules\rbac\controllers\frontend
 */
class DefaultController extends Controller
{
    /**
     * Права доступа по умолчанию, которые нельзя удалить
     * @var array
     */
    private $default = [
        'rbac',
    ];

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
                        'roles' => ['rbac'],
                    ],
                ],
            ]
        ];
    }

    /**
     * Вывод всех прав доступа
     * @return mixed
     */
    public function actionIndex()
    {
        $data = Yii::$app->authManager->getPermissions();
        $dataProvider = new ArrayDataProvider([
            'models' => $data,
            'totalCount' => count($data),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Создание нового правила
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PermissionsForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Изменение правила
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findPermission($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            Yii::$app->session->setFlash('success', 'Данные успешно сохранены.');
            return $this->redirect(['update', 'id' => $id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Удаление правила
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException если нет такой роли
     */
    public function actionDelete($id)
    {
        if (in_array($id, $this->default)) {
            throw new ForbiddenHttpException('Вы не можете удалить данное правило!');
        }

        $this->findPermission($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Массовое удаление правил
     * @return mixed
     */
    public function actionDeleteMultiple()
    {
        $selection = Yii::$app->request->post('selection', []);

        foreach ($selection as $rule) {
            $this->findPermission($rule)->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * Поиск правила, если не найдена возвращаем 404 ошибку
     * @param integer $id
     * @return PermissionsForm
     * @throws NotFoundHttpException если нет такой роли
     * @throws ForbiddenHttpException
     */
    protected function findPermission($id)
    {
        if (($rule = Yii::$app->authManager->getPermission($id)) !== null) {
            $model                   = new PermissionsForm(['scenario' => 'update']);
            $model->name             = $rule->description;
            $model->rule             = $rule->name;
            $model->last_name        = $rule->name;
            $model->oldAttributes    = $model->attributes;

            return $model;
        } else {
            throw new NotFoundHttpException('Роль не найдена');
        }
    }

}

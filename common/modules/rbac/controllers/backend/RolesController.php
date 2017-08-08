<?php

namespace modules\rbac\controllers\backend;

use common\components\VarDumper;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;
use modules\rbac\models\RolesForm;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

/**
 * Class RolesController
 * @package modules\rbac\controllers\frontend
 */
class RolesController extends Controller
{
    /**
     * Роли по умолчанию, их нельзя удалить
     * @var array
     */
    private $default = [
        'admin'
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
                    ]
                ],
            ]
        ];
    }

    /**
     * Вывод всех ролей
     * @return mixed
     */
    public function actionIndex()
    {
        $data = Yii::$app->authManager->getRoles();
        $dataProvider = new ArrayDataProvider([
            'models' => $data,
            'totalCount' => count($data)
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Создание роли
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RolesForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Изменение роли
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findRole($id);

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
     * Удаление роли
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException если нет такой роли
     */
    public function actionDelete($id)
    {
        if (in_array($id, $this->default)) {
            throw new ForbiddenHttpException('Вы не можете удалить данную роль!');
        }

        $this->findRole($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Массовое удаление ролей
     * @return mixed
     */
    public function actionDeleteMultiple()
    {
        $selection = Yii::$app->request->post('selection', []);

        foreach ($selection as $role) {
            $this->findRole($role)->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * Поиск роли, если не найдена возвращаем 404 ошибку
     * @param integer $id
     * @return object
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException если нет такой роли
     */
    protected function findRole($id)
    {
        if (($role = Yii::$app->authManager->getRole($id)) !== null) {
            $model = new RolesForm();
            $model->scenario = $model::SCENARIO_UPDATE;
            $model->name = $role->description;
            $model->alias = $role->name;
            $model->last_name = $role->name;
            $model->rule = $role->ruleName;
            $model->permissions = $model->loadPermissions();
            $model->oldAttributes = $model->attributes;

            return $model;
        } else {
            throw new NotFoundHttpException('Роль не найдена');
        }
    }

}

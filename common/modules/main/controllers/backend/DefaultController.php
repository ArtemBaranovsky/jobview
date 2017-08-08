<?php

namespace modules\main\controllers\backend;

use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * Class DefaultController
 * @package modules\main\controllers\backend
 */
class DefaultController extends Controller{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['task-manager'],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['task-manager'],
                        'roles'   => ['task-manager'],
                    ],
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ],
        ];
    }
    
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
<?php

namespace modules\main\controllers\frontend;

use common\components\Hash;
use common\components\VarDumper;
use modules\account\models\frontend\Account;
use modules\account\models\frontend\Analytics;
use modules\users\models\backend\search\Users;
use yii;
use yii\web\Controller;
use themes\landing\Theme;
use yii\filters\AccessControl;

/**
 * Class DefaultController
 * @package modules\main\controllers\frontend
 */
class DefaultController extends Controller{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->layout = Yii::$app->user->isGuest ? 'empty' : 'main';
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class'  => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest){
            $this->layout = 'main';
            Yii::$app->view->theme = new Theme();
            return $this->render('index-guest');
        }

        return $this->render('index');
    }

    /**
     * @return string
     */
    public function actionPolicy()
    {
        return $this->render('policy');
    }

    /**
     * @return string
     */
    public function actionOffer()
    {
        return $this->render('offer');
    }
}
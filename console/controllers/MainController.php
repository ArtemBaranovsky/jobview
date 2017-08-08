<?php

namespace console\controllers;

use yii;
use yii\console\Controller;

/**
 * Class MainController
 * @package console\controllers
 */
class MainController extends Controller {
    /**
     * Refresh schema cache
     */
    public function actionSchemaRefresh()
    {
        Yii::$app->db->schema->refresh();
        Yii::info('Refresh schema cache', 'app');
    }
}
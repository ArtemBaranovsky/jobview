<?php

namespace console\controllers;

use yii;
use GearmanWorker;
use yii\console\Controller;
use console\workers\SendMail;
use console\workers\SendVkMessage;

/**
 * Class WorkersController
 * @package console\controllers
 */
class WorkersController extends Controller {
    /**
     * Отправка писем на почту
     */
    public function actionSendEmail()
    {
        $servers = Yii::$app->params['gearman']['servers']['master'];

        $worker = new GearmanWorker();
        $worker->addServers($servers);
        $worker->addFunction('send-email', [new SendMail(), 'run']);

        $i = 0;
        while ($worker->work()){
            echo ++$i . ') ' . Yii::$app->formatter->asDatetime(time()) . PHP_EOL;
            usleep(50000);
        }
    }

    /**
     * Отправка писем VK
     */
    public function actionSendNotification()
    {
        $servers = Yii::$app->params['gearman']['servers']['master'];

        $worker = new GearmanWorker();
        $worker->addServers($servers);
        $worker->addFunction('send-notification', [new SendVkMessage(), 'run']);

        $i = 0;
        while ($worker->work()){
            echo ++$i . ') ' . Yii::$app->formatter->asDatetime(time()) . PHP_EOL;
        }
    }
}
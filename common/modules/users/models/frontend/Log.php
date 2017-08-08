<?php

namespace modules\users\models\frontend;

use common\components\VarDumper;
use yii;
use modules\users\models\BaseLog;

/**
 * Class Log
 * @package modules\users\models\frontend
 */
class Log extends BaseLog {
    /**
     * Запуск задачи
     */
    const RUN_TASK = 'runTask';
    /**
     * Добавление задачи
     */
    const CREATE_TASK = 'createTask';
    /**
     * Добавление аккаунта
     */
    const CREATE_ACCOUNT = 'createAccount';
    /**
     * Активация учетной записи
     */
    const ACTIVATE_ACCOUNT = 'activate_account';
    /**
     * @var bool
     */
    protected static $step = false;

    /**
     * Проверяем, был выполнен определенный шаг или нет
     * @param $action
     * @return bool|mixed
     */
    public static function hasStep($action)
    {
        /**
         * Если данные о шагах не получены
         */
        if (self::$step === false){
            $data = [
                self::RUN_TASK       => false,
                self::CREATE_TASK    => false,
                self::CREATE_ACCOUNT => false,
            ];
            $all = self::find()
                ->where([
                    'user_id' => Yii::$app->user->id,
                    'message' => [
                        'CREATE_ACCOUNT_{id}',
                        'CREATE_TASK_{name}',
                        'RUN_TASK_{name}',
                        'RESTART_TASK_{name}',
                        'ACTIVATE_ACCOUNT',
                    ]
                ])
                ->groupBy(['message'])
                ->limit(5)
                ->all();

            foreach ($all as $step){
                if (in_array($step->message, ['RUN_TASK_{name}', 'RESTART_TASK_{name}'])){
                    /**
                     * Запуск задачи
                     */
                    $data[self::RUN_TASK] = true;
                }elseif ($step->message == 'CREATE_TASK_{name}'){
                    /**
                     * Добавление задачи
                     */
                    $data[self::CREATE_TASK] = true;
                }elseif ($step->message == 'CREATE_ACCOUNT_{id}'){
                    /**
                     * Добавление аккаунта
                     */
                    $data[self::CREATE_ACCOUNT] = true;
                }elseif ($step->message == 'ACTIVATE_ACCOUNT'){
                    /**
                     * Активация учетной записи
                     */
                    $data[self::ACTIVATE_ACCOUNT] = true;
                }
            }

            self::$step = $data;
        }

        return isset(self::$step[$action]) ? self::$step[$action] : false;
    }
}
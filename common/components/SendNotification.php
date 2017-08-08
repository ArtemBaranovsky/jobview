<?php

namespace common\components;

use yii;
use Exception;
use GearmanClient;

/**
 * Class SendNotification
 * @package common\components
 */
class SendNotification {
    /**
     * Название функции воркера
     */
    const WORKER_FUNCTION = 'send-notification';
    /**
     * Оповещения о новых тикетах и сообщениях в поддержке
     */
    const CHAT_SUPPORT = 'support';
    /**
     * Оповещения о регистрации
     */
    const CHAT_REGISTRATION = 'registration';
    /**
     * Оповещения о пополнении баланса
     */
    const CHAT_PAYMENT = 'payment';
    /**
     * ID Чата ВК
     * @var
     */
    public $chat_id;
    /**
     * Сообщение
     * @var
     */
    public $message;

    /**
     * Отправляем письмо
     */
    public function send()
    {
        $servers = Yii::$app->params['gearman']['servers']['master'];

        $client = new GearmanClient();
        $client->addServers($servers);
        $client->doBackground(self::WORKER_FUNCTION, $this->getData());
    }

    /**
     * Получаем данные для отправки задачи на воркер
     * @return string
     * @throws \Exception
     */
    protected function getData()
    {
        if (isset(Yii::$app->params['vk']['chat'][$this->chat_id]) === false){
            throw new Exception('Не найден chat_id');
        }

        $data = [
            'message' => $this->message,
            'chat_id' => Yii::$app->params['vk']['chat'][$this->chat_id],
        ];

        return json_encode($data);
    }
}
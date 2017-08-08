<?php

namespace common\components;

use yii;
use GearmanClient;

/**
 * Class SendEmail
 * @package common\components
 */
class SendMail {
    /**
     * Название функции воркера
     */
    const WORKER_FUNCTION = 'send-email';
    /**
     * Низкий приоритет
     */
    const PRIORITY_LOW = 'low';
    /**
     * Высокий приоритет
     */
    const PRIORITY_HIGHT = 'hight';
    /**
     * Кому
     * @var
     */
    public $to;
    /**
     * Тема
     * @var
     */
    public $subject;
    /**
     * Html сообщения
     * @var
     */
    public $html;
    /**
     * Текст сообщения
     * @var
     */
    public $text;

    /**
     * Отправляем письмо
     * @param string $priority
     */
    public function send($priority = self::PRIORITY_HIGHT)
    {
        $servers = Yii::$app->params['gearman']['servers']['master'];

        $client = new GearmanClient();
        $client->addServers($servers);

        if ($priority == self::PRIORITY_HIGHT){
            $client->doHighBackground(self::WORKER_FUNCTION, $this->getData());
        }else{
            $client->doLowBackground(self::WORKER_FUNCTION, $this->getData());
        }
    }

    /**
     * Получаем данные для отправки задачи на воркер
     * @return array
     */
    protected function getData()
    {
        $data = [
            'to'      => $this->to,
            'html'    => $this->html,
            'text'    => $this->text,
            'subject' => $this->subject,
        ];

        return json_encode($data);
    }
}
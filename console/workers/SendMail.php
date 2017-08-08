<?php

namespace console\workers;

use yii;
use common\components\SendPulseSmtpApi;

/**
 * Class SendMail
 * @package console\models
 */
class SendMail {
    /**
     * @param $job \GearmanJob
     */
    public function run($job)
    {
        $data = json_decode($job->workload(), true);

        $send = $this->send($data);

        if (isset($send['error']) && $send['error'] == 0){
            echo "\tOk\n";
        }else if (isset($send['text'])){
            echo "\tError: " . $send['text'] . "\n";
            Yii::error($send['text'], 'worker_sendMail');
        }else{
            echo "\tError: not error text\n";
            Yii::error('Not error text', 'worker_sendMail');
        }
    }

    /**
     * @param $data
     * @return mixed
     */
    public function send($data)
    {
        $to      = $data['to'];
        $html    = $data['html'];
        $text    = $data['text'];
        $subject = $data['subject'];

        $api = new SendPulseSmtpApi(Yii::$app->params['sendPulse']['smtp']['public_key']);

        $data = [
            'html'      => $html,
            'text'      => $text,
            'encoding'  => Yii::$app->charset,
            'subject'   => $subject,
            'from'      => [
                'name'  => Yii::$app->name,
                'email' => Yii::$app->params['email'],
            ],
            'to' => [
                [
                    'email' => $to,
                ]
            ],
        ];

        return $api->send_email($data);
    }
}
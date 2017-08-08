<?php

namespace console\workers;

use yii;
use Exception;
use common\components\Vk;

/**
 * Class SendVkMessage
 * @package console\workers
 */
class SendVkMessage {
    /**
     * @param $job \GearmanJob
     */
    public function run($job)
    {
        $data = json_decode($job->workload(), true);

        try {
            $this->send($data);
            echo "\tOk\n";
        }catch (\Exception $e){
            echo "\tError " . $e->getMessage() . "\n";
            Yii::error($e->getMessage(), 'notification');
        };
    }

    /**
     * @param $data
     * @return bool
     * @throws \Exception
     */
    protected function send($data)
    {
        /* @var $vk Vk */
        $vk = Yii::$app->vk;
        $vk->access_token = Yii::$app->params['vk']['access_token'];

        $response = $vk->api('messages.send', [
            'chat_id' => $data['chat_id'],
            'message' => $data['message']
        ]);

        if (isset($response['error'])){
            throw new Exception($response['error']['error_msg']);
        }

        return true;
    }
}
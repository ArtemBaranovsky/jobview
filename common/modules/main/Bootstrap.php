<?php

namespace modules\main;

use yii\base\BootstrapInterface;

/**
 * Class Bootstrap
 * @package modules\main
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app->id === 'frontend'){
            $rules['']                   = 'main/default/index';
            $rules['error']              = 'main/default/error';
            $rules['content/policy']     = 'main/default/policy';
            $rules['content/offer']      = 'main/default/offer';
            $app->urlManager->addRules($rules, false);
        }
    }
}
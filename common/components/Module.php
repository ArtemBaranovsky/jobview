<?php

namespace common\components;

use yii;

class Module extends yii\base\Module {
    /**
     * @inheritdoc
     */
    public function init()
    {
        $app = Yii::$app->id;

        $this->setLayoutPath('@' . $app . '/views/layouts');
        $this->setViewPath('@modules/' . $this->id . '/views/' . $app);
        $this->controllerNamespace = $this->controllerNamespace ?? '\modules\\' . $this->id . '\controllers\\' . $app;
    }
}
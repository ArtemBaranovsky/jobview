<?php

namespace themes\landing;

use yii;

/**
 * Class Theme
 * @package themes\landing
 */
class Theme extends yii\base\Theme {
    /**
     * @inheritdoc
     */
    public $pathMap = [
        '@frontend/views' => '@themes/landing/views/frontend',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
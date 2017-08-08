<?php

namespace modules\_template;

use yii;

class Module extends \common\components\Module {
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    /**
     * Регистрируем перевод.
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/_template/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@modules/_template/messages',
            'fileMap' => [
                'modules/_template/main' => 'main.php'
            ]
        ];
    }

    /**
     * Переводим фразу.
     * @param $category
     * @param $message
     * @param array $params
     * @param null $language
     * @return string
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/_template/' . $category, $message, $params, $language);
    }
}
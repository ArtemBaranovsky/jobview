<?php

namespace modules\rbac\models;

use yii;
use yii\rbac\Rule;
use yii\base\Model;
use modules\users\models\backend\Log;

/**
 * Class RulesForm
 * @package modules\rbac\models
 */
class RulesForm extends Model
{
    /**
     * @var string
     */
    public $namespace;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['namespace', 'required'],
            ['namespace', 'validateNamespace']
        ];
    }

    /**
     * Валидация namespace
     * @param $attribute
     * @param $params
     */
    public function validateNamespace($attribute, $params)
    {
        $hasError = false;

        if (class_exists($this->{$attribute})) {
            $rule = new $this->{$attribute};

            if (!$rule instanceof Rule) {
                $hasError = true;
            }
        } else {
            $hasError = true;
        }

        if ($hasError === true) {
            $this->addError($attribute, 'Неверный namespace');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'namespace' => 'Namespace'
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterValidate()
    {
        if (parent::hasErrors() === false) {
            $auth = Yii::$app->authManager;
            $rule = new $this->namespace;
            $rule->name = end(explode('\\', $this->namespace));
            $auth->add($rule);

            Log::create(Log::TYPE_INSERT, 'CREATE_RBAC_RULE_{alias}', ['alias' => $this->namespace],
                [],
                $this->attributes,
                ['namespace']
            );
        }
        parent::afterValidate();
    }
}

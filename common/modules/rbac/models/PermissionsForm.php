<?php

namespace modules\rbac\models;

use common\components\VarDumper;
use yii;
use yii\base\Model;
use modules\users\models\backend\Log;

/**
 * Class PermissionsForm
 * @package modules\rbac\models
 */
class PermissionsForm extends Model
{
    /**
     * Сценарий редактирования прав доступа
     */
    const SCENARIO_UPDATE = 'update';
    /**
     * Название права
     * @var string
     */
    public $name;
    /**
     * Алиас права
     * @var string
     */
    public $rule;
    /**
     * Предыдущий алиас права
     * @var string
     */
    public $last_name;
    /**
     * @var array
     */
    public $oldAttributes = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'rule'], 'required'],
            [['name', 'rule'], 'string', 'max' => 50],
            [['rule'], 'isUnique'],
        ];
    }

    /**
     * @param string $attribute
     * @param array $params
     */
    public function isUnique($attribute, $params)
    {
        if ($this->last_name != $this->$attribute && Yii::$app->authManager->getPermission($this->$attribute) != null) {
            $this->addError($attribute, 'Такое право уже существует');
        }
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['name', 'rule'],
            self::SCENARIO_UPDATE => ['name', 'rule'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Описание',
            'rule' => 'Алиас'
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterValidate()
    {
        if (parent::hasErrors() === false) {
            $this->savePermission();
        }
        parent::afterValidate();
    }

    /**
     * Удаляем право
     * @return bool
     */
    public function delete()
    {
        $auth = Yii::$app->authManager;
        $rule = $auth->getPermission($this->rule);

        if ($auth->remove($rule)){
            Log::create(Log::TYPE_DELETE, 'DELETE_RBAC_PERMISSION_{alias}', ['alias' => $this->rule],
                $this->oldAttributes,
                $this->attributes,
                ['name', 'rule']
            );
            return true;
        }

        return false;
    }

    /**
     * Сохраняем право
     * @return void
     */
    private function savePermission()
    {
        $auth = Yii::$app->authManager;

        if ($this->scenario == self::SCENARIO_UPDATE) {
            $permission = $auth->getPermission($this->last_name);
            $permission->description = $this->name;
            $auth->update($this->last_name, $permission);

            Log::create(Log::TYPE_UPDATE, 'UPDATE_RBAC_PERMISSION_{alias}', ['alias' => $this->rule],
                $this->oldAttributes,
                $this->attributes,
                ['name', 'rule']
            );
        } else {
            $permission = $auth->createPermission($this->rule);
            $permission->description = $this->name;
            $auth->add($permission);

            Log::create(Log::TYPE_INSERT, 'CREATE_RBAC_PERMISSION_{alias}', ['alias' => $this->rule],
                $this->oldAttributes,
                $this->attributes,
                ['name', 'rule']
            );
        }
    }
}
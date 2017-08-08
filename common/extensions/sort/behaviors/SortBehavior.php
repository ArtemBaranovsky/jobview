<?php

namespace common\extensions\sort\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

class SortBehavior extends Behavior{
    /**
     * Аттрибут порядка сортировки
     * @var string
     */
    public $attribute = 'sort';
    /**
     * Дополнительные параметры для поиска предыдущих записей.
     * @var array
     */
    public $modelParams = [];

    /**
     * Получаем последний порядковый номер сортировки
     * @return int
     */
    protected function getSortedNumber()
    {
        $prev = $this->owner->find()->orderBy('sort DESC');

        if (count($this->modelParams) > 0){
            foreach ($this->modelParams as $attribute){
                $prev->andWhere([
                    $attribute => $this->owner->{$attribute}
                ]);
            }
        }
        $prev = $prev->one();

        if ($prev !== null){
            return ++$prev->sort;
        }
        return 1;
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert'
        ];
    }

    /**
     * Вставляем порядок сортировки перед добавлением записи
     */
    public function beforeInsert()
    {
        $this->owner->{$this->attribute} = $this->getSortedNumber();
    }
}
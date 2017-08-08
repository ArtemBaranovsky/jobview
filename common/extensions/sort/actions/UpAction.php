<?php

namespace common\extensions\sort\actions;

use Yii;
use yii\base\Action;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;
use yii\base\InvalidConfigException;

class UpAction extends Action{
    /**
     * Модель записи которую будем перемещать вверх
     * @var ActiveRecord
     */
    public $model;
    /**
     * Куда редиректим после перемещения
     * @var array
     */
    public $redirect;
    /**
     * Дополнительные параметры поиска предыдущей записи.
     * @var array
     */
    public $modelParams = [];
    /**
     * Атрибут который отвечает за порядок сортировки
     * @var string
     */
    public $attribute = 'sort';

    /**
     * Ищем запись по id
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = (new $this->model)->findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    /**
     * Перемещаем запись на позицию вверх
     * @param $id
     */
    protected function move($id)
    {
        $model = $this->findModel($id);
        $prev = (new $this->model)->find()->where('sort < :sort', [':sort' => $model->sort])->orderBy('sort DESC');
        if (count($this->modelParams) > 0){
            foreach ($this->modelParams as $attribute){
                $prev->andWhere([
                    $attribute => $model->{$attribute}
                ]);
            }
        }
        $prev = $prev->one();

        if ($prev !== null){
            $tmp = $model->sort;
            $model->sort = $prev->sort;
            $prev->sort = $tmp;
            $model->save();
            $prev->save();
        }
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->model === null){
            throw new InvalidConfigException('Не указано свойство "model"');
        }elseif ($this->redirect === null){
            throw new InvalidConfigException('Не указано свойство "redirect"');
        }
    }

    /**
     * @inheritdoc
     */
    public function run($id)
    {
        $this->move($id);
        return Yii::$app->response->redirect(Url::to($this->redirect));
    }
}
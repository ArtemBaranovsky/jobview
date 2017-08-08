<?php

namespace common\extensions\sort\actions;

use Yii;
use yii\base\Action;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;
use yii\base\InvalidConfigException;

class DownAction extends Action{
    /**
     * Модель записи которую будем перемещать вниз
     * @var ActiveRecord
     */
    public $model;
    /**
     * Куда редиректим после перемещения
     * @var array
     */
    public $redirect;
    /**
     * Дополнительные параметры поиска следующей записи.
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
     * Перемещаем запись на позицию вниз
     * @param $id
     */
    protected function move($id)
    {
        $model = $this->findModel($id);
        $next = (new $this->model)->find()->where('sort > :sort', [':sort' => $model->sort])->orderBy('sort ASC');

        if (count($this->modelParams) > 0){
            foreach ($this->modelParams as $attribute){
                $next->andWhere([
                    $attribute => $model->{$attribute}
                ]);
            }
        }
        $next = $next->one();

        if ($next !== null){
            $tmp = $model->sort;
            $model->sort = $next->sort;
            $next->sort = $tmp;
            $model->save();
            $next->save();
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
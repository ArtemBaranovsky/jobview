<?php

namespace modules\users\models\backend\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use modules\users\models\backend\Log as LogModel;

/**
 * Log represents the model behind the search form about `modules\users\models\backend\Log`.
 */
class Log extends LogModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'created_at'], 'integer'],
            [['type', 'message', 'params', 'data_old', 'data_new', 'url', 'ua', 'ip'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = LogModel::find()->with('user');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'params', $this->params])
            ->andFilterWhere(['like', 'data_old', $this->data_old])
            ->andFilterWhere(['like', 'data_new', $this->data_new])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'ua', $this->ua])
            ->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
}

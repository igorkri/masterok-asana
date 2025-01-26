<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Timer;

/**
 * TimerSearch represents the model behind the search form about `common\models\Timer`.
 */
class TimerSearch extends Timer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'minute', 'status'], 'integer'],
            [['task_gid', 'time', 'comment', 'created_at', 'updated_at'], 'safe'],
            [['coefficient'], 'number'],
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
        $query = Timer::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'time' => $this->time,
            'minute' => $this->minute,
            'coefficient' => $this->coefficient,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'task_gid', $this->task_gid])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}

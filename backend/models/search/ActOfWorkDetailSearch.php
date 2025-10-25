<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ActOfWorkDetail;

/**
 * ActOfWorkDetailSearch represents the model behind the search form of `common\models\ActOfWorkDetail`.
 */
class ActOfWorkDetailSearch extends ActOfWorkDetail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'act_of_work_id', 'time_id', 'task_gid', 'project_gid'], 'integer'],
            [['project', 'task', 'description', 'created_at', 'updated_at'], 'safe'],
            [['amount', 'hours'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = ActOfWorkDetail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'act_of_work_id' => $this->act_of_work_id,
            'time_id' => $this->time_id,
            'task_id' => $this->task_gid,
            'project_id' => $this->project_gid,
            'amount' => $this->amount,
            'hours' => $this->hours,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'project', $this->project])
            ->andFilterWhere(['like', 'task', $this->task])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}

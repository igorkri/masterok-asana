<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ActWorkLog;

/**
 * ActWorkLogSearch represents the model behind the search form about `common\models\ActWorkLog`.
 */
class ActWorkLogSearch extends ActWorkLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'act_of_work_id', 'act_of_work_detail_id', 'timer_id', 'task_id', 'project_id'], 'integer'],
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
        $query = ActWorkLog::find();

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
            'act_of_work_id' => $this->act_of_work_id,
            'act_of_work_detail_id' => $this->act_of_work_detail_id,
            'timer_id' => $this->timer_id,
            'task_id' => $this->task_id,
            'project_id' => $this->project_id,
        ]);

        return $dataProvider;
    }
}

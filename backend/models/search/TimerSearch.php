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
     *
     * $_GET = [
     * 'TimerSearch' => [
     * 'task_gid' => '',
     * 'time' => '',
     * 'coefficient' => '',
     * 'comment' => '',
     * 'status' => [
     * '1',
     * ],
     * 'created_at' => '',
     * 'updated_at' => '',
     * 'minute' => '',
     * ],
     * 'hour' => '',
     * 'minute' => '',
     * 'second' => '',
     * 'meridian' => '',
     * ];
 */
    public function rules()
    {
        return [
            [['id', 'minute', 'status'], 'integer'],
            [['task_gid', 'time', 'comment', 'created_at', 'updated_at'], 'safe'],
            [['coefficient'], 'number'],
//            [['status'], 'each', 'rule' => ['in', 'range' => array_keys(self::$statusList)]],
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
//        debugDie($this->status);

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


        // ищем в диапазоне дат (01.01.2025 - 23.01.2025) - это в формате даты, нужно преобразовать в формат даты для БД (2025-01-01 - 2025-01-23)
        if (isset($params['TimerSearch']['created_at']) && !empty($params['TimerSearch']['created_at'])) {
            $date = explode(' - ', $params['TimerSearch']['created_at']);
            $query->andFilterWhere(['between', 'created_at', date('Y-m-d', strtotime($date[0])), date('Y-m-d', strtotime($date[1]))]);
        }

        // ищем в диапазоне дат (01.01.2025 - 23.01.2025) - это в формате даты, нужно преобразовать в формат даты для БД (2025-01-01 - 2025-01-23)
        if (isset($params['TimerSearch']['updated_at']) && !empty($params['TimerSearch']['updated_at'])) {
            $date = explode(' - ', $params['TimerSearch']['updated_at']);
            $query->andFilterWhere(['between', 'updated_at', date('Y-m-d', strtotime($date[0])), date('Y-m-d', strtotime($date[1]))]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'time' => $this->time,
            'minute' => $this->minute,
            'coefficient' => $this->coefficient,
            'status' => $this->status,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
        ]);

//        if (is_array($this->status) && !empty($this->status)) {
//            $query->andFilterWhere(['IN', 'status', $this->status]);
//        } elseif (!empty($this->status)) {
//            debugDie($this->status);
//            $query->andFilterWhere(['status' => $this->status]);
//        }



        $query->andFilterWhere(['like', 'task_gid', $this->task_gid])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}

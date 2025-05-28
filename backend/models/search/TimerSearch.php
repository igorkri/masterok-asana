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

    public $exclude;
    public $project_id;

    public function rules()
    {
        return [
            [['id', 'minute', 'status'], 'integer'],
            [['task_gid', 'time', 'comment', 'created_at', 'updated_at', 'project_id', 'exclude'], 'safe'],
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
        $query = Timer::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
//            debugDie($this->errors);
            return $dataProvider;
        }




        // Фильтрация по проектам (включение / исключение)
        if (!empty($this->project_id)) {
            // создем сессию для фильтрации по проектам
            $nameSession = 'advanced-filter';
            Yii::$app->session->set($nameSession, ['projectIds' => $this->project_id, 'exclude' => $this->exclude]);

            // project_id может быть строкой (если один выбран), приводим к массиву
            $projectIds = (array)$this->project_id;

            $query->joinWith(['taskG.project']); // Связь к проекту

            if ($this->exclude === 'yes') {
                $query->andWhere(['not in', 'project.id', $projectIds]);
            } else {
                $query->andWhere(['in', 'project.id', $projectIds]);
            }
        }

//        // Диапазон по created_at
//        if (!empty($this->created_at) && strpos($this->created_at, ' - ') !== false) {
//            list($start, $end) = explode(' - ', $this->created_at);
//            $query->andFilterWhere(['between', 'created_at', date('Y-m-d', strtotime($start)), date('Y-m-d', strtotime($end))]);
//        }
//
//        // Диапазон по updated_at
//        if (!empty($this->updated_at) && strpos($this->updated_at, ' - ') !== false) {
//            list($start, $end) = explode(' - ', $this->updated_at);
//            $query->andFilterWhere(['between', 'updated_at', date('Y-m-d', strtotime($start)), date('Y-m-d', strtotime($end))]);
//        }

        if (!empty($this->created_at) && strpos($this->created_at, ' - ') !== false) {
            list($start, $end) = explode(' - ', $this->created_at);
            $query->andFilterWhere(['between', 'timer.created_at', date('Y-m-d', strtotime($start)), date('Y-m-d', strtotime($end))]);
        }

        if (!empty($this->updated_at) && strpos($this->updated_at, ' - ') !== false) {
            list($start, $end) = explode(' - ', $this->updated_at);
            $query->andFilterWhere(['between', 'timer.updated_at', date('Y-m-d', strtotime($start)), date('Y-m-d', strtotime($end))]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'time' => $this->time,
            'minute' => $this->minute,
            'coefficient' => $this->coefficient,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'task_gid', $this->task_gid])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }



//    public function search($params)
//    {
////        debugDie($this->status);
//
//        $query = Timer::find();
//
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//        ]);
//
//        $this->load($params);
//
//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }
//
//
//        Yii::warning($params, '$params');
//
//        if (isset($params['TimerSearch']['exclude']) && !empty($params['TimerSearch']['exclude'])) {
//            $query->joinWith('taskG.project');
//            if ($params['TimerSearch']['exclude'] == 'no') {
//                $query->andWhere(['in', 'project.id', $params['TimerSearch']['project_id']]);
//            } else {
//                $query->andWhere(['not in', 'project.id', $params['TimerSearch']['project_id']]);
//            }
//        }
//
//        // ищем в диапазоне дат (01.01.2025 - 23.01.2025) - это в формате даты, нужно преобразовать в формат даты для БД (2025-01-01 - 2025-01-23)
//        if (isset($params['TimerSearch']['created_at']) && !empty($params['TimerSearch']['created_at'])) {
//            $date = explode(' - ', $params['TimerSearch']['created_at']);
//            $query->andFilterWhere(['between', 'created_at', date('Y-m-d', strtotime($date[0])), date('Y-m-d', strtotime($date[1]))]);
//        }
//
//        // ищем в диапазоне дат (01.01.2025 - 23.01.2025) - это в формате даты, нужно преобразовать в формат даты для БД (2025-01-01 - 2025-01-23)
//        if (isset($params['TimerSearch']['updated_at']) && !empty($params['TimerSearch']['updated_at'])) {
//            $date = explode(' - ', $params['TimerSearch']['updated_at']);
//            $query->andFilterWhere(['between', 'updated_at', date('Y-m-d', strtotime($date[0])), date('Y-m-d', strtotime($date[1]))]);
//        }
//
//        $query->andFilterWhere([
//            'id' => $this->id,
//            'time' => $this->time,
//            'minute' => $this->minute,
//            'coefficient' => $this->coefficient,
//            'status' => $this->status,
////            'created_at' => $this->created_at,
////            'updated_at' => $this->updated_at,
//        ]);
//
////        if (is_array($this->status) && !empty($this->status)) {
////            $query->andFilterWhere(['IN', 'status', $this->status]);
////        } elseif (!empty($this->status)) {
////            debugDie($this->status);
////            $query->andFilterWhere(['status' => $this->status]);
////        }
//
//
//        $query->andFilterWhere(['like', 'task_gid', $this->task_gid])
//            ->andFilterWhere(['like', 'comment', $this->comment]);
//
//        return $dataProvider;
//    }
}

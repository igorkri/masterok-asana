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
            [['id', 'minute', 'status', 'archive'], 'integer'],
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

        // Фильтрация по archive
        if (isset($this->archive) && $this->archive !== '') {
            $query->andFilterWhere(['archive' => $this->archive]);
        } else {
            // Если archive не указан, то исключаем архивные записи
            $query->andWhere(['archive' => 0]);
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

}

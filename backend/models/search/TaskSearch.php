<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Task;

/**
 * TaskSearch represents the model behind the search form about `common\models\Task`.
 */
class TaskSearch extends Task
{

    public $created_at_from;
    public $created_at_to;
    public $modified_at_from;
    public $modified_at_to;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'num_hearts', 'num_likes'], 'integer'],
            [['gid', 'name', 'assignee_gid', 'assignee_name', 'assignee_status', 'section_project_gid', 'section_project_name', 'completed', 'completed_at', 'created_at', 'due_on', 'start_on', 'notes', 'permalink_url', 'project_gid', 'workspace_gid', 'modified_at', 'resource_subtype'], 'safe'],
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
        $query = Task::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

//        debugDie($this->assignee_name);
        $query->andFilterWhere([
            'id' => $this->id,
            'completed_at' => $this->completed_at,
//            'created_at' => $this->created_at,
            'due_on' => $this->due_on,
            'start_on' => $this->start_on,
//            'modified_at' => $this->modified_at,
            'num_hearts' => $this->num_hearts,
            'num_likes' => $this->num_likes,
            'project_gid' => $this->project_gid,
//            'assignee_name' => explode(',', $this->assignee_name[0])
        ]);

            // Фильтрация по дате создания
            if ($this->created_at) {
                $date = explode(' - ', $this->created_at);
                $this->created_at_from = date('Y-m-d' . ' 00:00:00', strtotime($date[0]));
                $this->created_at_to = date('Y-m-d' . ' 23:59:59', strtotime($date[1]));
//                Yii::warning([$this->created_at_from, $this->created_at_to], 'created_at');
                $query->andFilterWhere(['between', 'created_at', $this->created_at_from, $this->created_at_to]);
            }

            // Фильтрация по дате обновления
            if ($this->modified_at) {
                $date = explode(' - ', $this->modified_at);
                $this->modified_at_from = date('Y-m-d' . ' 00:00:00', strtotime($date[0]));
                $this->modified_at_to = date('Y-m-d' . ' 23:59:59', strtotime($date[1]));

                $query->andFilterWhere(['between', 'modified_at', $this->modified_at_from, $this->modified_at_to]);
            }

        $query->andFilterWhere(['like', 'gid', $this->gid])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'assignee_gid', $this->assignee_gid])
            ->andFilterWhere(['like', 'assignee_status', $this->assignee_status])
            ->andFilterWhere(['like', 'section_project_gid', $this->section_project_gid])
//            ->andFilterWhere(['like', 'section_project_name', $this->section_project_name])
            ->andFilterWhere(['like', 'completed', $this->completed])
            ->andFilterWhere(['like', 'notes', $this->notes])
            ->andFilterWhere(['like', 'permalink_url', $this->permalink_url])
//            ->andFilterWhere(['like', 'project_gid', $this->project_gid])
            ->andFilterWhere(['like', 'workspace_gid', $this->workspace_gid])
            ->andFilterWhere(['like', 'resource_subtype', $this->resource_subtype]);

        if ($this->assignee_name) {
            $this->assignee_name = // поудалять значения у которых 0
                array_filter($this->assignee_name, function ($value) {
                    return $value !== '0';
                });
            $query->andFilterWhere(['IN', 'assignee_name', $this->assignee_name]);
        }
        if ($this->section_project_name) {
            $this->section_project_name = // поудалять значения у которых 0
                array_filter($this->section_project_name, function ($value) {
                    return $value !== '0';
                });
            $query->andFilterWhere(['IN', 'section_project_name', $this->section_project_name]);
        }
Yii::warning($query->createCommand()->rawSql, 'sql search');
        return $dataProvider;
    }
}

<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ActOfWork;

/**
 * ActOfWorkSearch represents the model behind the search form of `common\models\ActOfWork`.
 */
class ActOfWorkSearch extends ActOfWork
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'sort'], 'integer'],
            [['number', 'status', 'period', 'date', 'description', 'file_excel', 'created_at', 'updated_at'], 'safe'],
            [['total_amount', 'paid_amount'], 'number'],
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
        $query = ActOfWork::find();

        // Проверяем, есть ли данные в сессии
        $session = \Yii::$app->session;
//                debugDie($session->get('bulk-hide'));
        if ($session->has('bulk-hide')) {
            $hideIds = $session->get('bulk-hide', []);

            if (!empty($hideIds)) {
                // Если есть, фильтруем запрос
                $query->andWhere(['not in', 'id', explode(',', $hideIds)]);
            }
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // order by sort
        $query->orderBy(['sort' => SORT_ASC, 'id' => SORT_DESC]);


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
            'date' => $this->date,
            'total_amount' => $this->total_amount,
            'paid_amount' => $this->paid_amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'period', $this->period])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'file_excel', $this->file_excel]);

        return $dataProvider;
    }
}

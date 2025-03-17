<?php

namespace backend\models\search\report;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\report\Payer;

/**
 * PayerSearch represents the model behind the search form about `common\models\report\Payer`.
 */
class PayerSearch extends Payer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'email', 'phone', 'contract', 'director', 'director_case', 'requisites', 'created_at'], 'safe'],
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
        $query = Payer::find();

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
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'contract', $this->contract])
            ->andFilterWhere(['like', 'director', $this->director])
            ->andFilterWhere(['like', 'director_case', $this->director_case])
            ->andFilterWhere(['like', 'requisites', $this->requisites]);

        return $dataProvider;
    }
}

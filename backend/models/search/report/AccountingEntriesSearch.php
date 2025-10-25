<?php

namespace backend\models\search\report;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AccountingEntries;

/**
 * AccountingEntriesSearch represents the model behind the search form about `common\models\AccountingEntries`.
 */
class AccountingEntriesSearch extends AccountingEntries
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['number', 'counterparty', 'description', 'document_at', 'created_at'], 'safe'],
            [['debit', 'credit'], 'number'],
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
        $query = AccountingEntries::find();

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
            'debit' => $this->debit,
            'credit' => $this->credit,
            'document_at' => $this->document_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'counterparty', $this->counterparty])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}

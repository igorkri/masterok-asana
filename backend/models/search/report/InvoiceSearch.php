<?php

namespace backend\models\search\report;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\report\Invoice;

/**
 * InvoiceSearch represents the model behind the search form about `common\models\report\Invoice`.
 */
class InvoiceSearch extends Invoice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'payer_id', 'invoice_no', 'act_no', 'qty'], 'integer'],
            [['date_invoice', 'date_act', 'title_invoice', 'title_act', 'created_at', 'updated_at'], 'safe'],
            [['amount'], 'number'],
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
        $query = Invoice::find();

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
        if (isset($params['InvoiceSearch']['date_invoice']) && !empty($params['InvoiceSearch']['date_invoice'])) {
            $date = explode(' - ', $params['InvoiceSearch']['date_invoice']);
            $query->andFilterWhere(['between', 'date_invoice', date('Y-m-d', strtotime($date[0])), date('Y-m-d', strtotime($date[1]))]);
        }

        // ищем в диапазоне дат (01.01.2025 - 23.01.2025) - это в формате даты, нужно преобразовать в формат даты для БД (2025-01-01 - 2025-01-23)
        if (isset($params['InvoiceSearch']['date_act']) && !empty($params['InvoiceSearch']['date_act'])) {
            $date = explode(' - ', $params['InvoiceSearch']['date_act']);
            $query->andFilterWhere(['between', 'date_act', date('Y-m-d', strtotime($date[0])), date('Y-m-d', strtotime($date[1]))]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'payer_id' => $this->payer_id,
            'invoice_no' => $this->invoice_no,
            'act_no' => $this->act_no,
            'qty' => $this->qty,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title_invoice', $this->title_invoice]);
        $query->andFilterWhere(['like', 'title_act', $this->title_act]);

        return $dataProvider;
    }
}

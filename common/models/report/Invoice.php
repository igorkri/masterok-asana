<?php

namespace common\models\report;

use Yii;

/**
 * This is the model class for table "invoice".
 *
 * @property int $id
 * @property int $payer_id
 * @property int|null $invoice_no
 * @property int|null $act_no
 * @property string|null $date_invoice
 * @property string|null $date_act
 * @property string|null $title_invoice
 * @property string|null $title_act
 * @property int $qty
 * @property float $amount
 * @property string|null $created_at Дата створення
 * @property string|null $updated_at Дата оновлення
 *
 * @property Payer $payer
 */
class Invoice extends \yii\db\ActiveRecord
{


    const PAGE_TYPE_INVOICE = 'invoice';
    const PAGE_TYPE_ACT = 'act';
    const PAGE_TYPE_ALL = 'all';

    public static function getPageTypeList()
    {
        return [
            self::PAGE_TYPE_INVOICE => 'Рахунки',
            self::PAGE_TYPE_ACT => 'Акти',
            self::PAGE_TYPE_ALL => 'Всі',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payer_id', 'qty', 'amount'], 'required'],
            [['payer_id', 'invoice_no', 'act_no', 'qty'], 'integer'],
            [['date_invoice', 'date_act', 'created_at', 'updated_at'], 'safe'],
            [['amount'], 'number'],
            [['title_invoice', 'title_act'], 'string', 'max' => 255],
            [['payer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Payer::class, 'targetAttribute' => ['payer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payer_id' => 'Платник',
            'invoice_no' => '№ рахунку',
            'act_no' => '№ акту',
            'date_invoice' => 'Дата рахунку',
            'date_act' => 'Дата акту',
            'title_invoice' => 'Назва рахунку',
            'title_act' => 'Назва акту',
            'qty' => 'Кількість',
            'amount' => 'Сума',
            'created_at' => 'Дата створення',
            'updated_at' => 'Дата оновлення',
        ];
    }

    /**
     * Gets query for [[Payer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayer()
    {
        return $this->hasOne(Payer::class, ['id' => 'payer_id']);
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "accounting_entries".
 *
 * @property int $id
 * @property string $number Номер документа
 * @property string|null $counterparty Кореспондент
 * @property float $debit Сума дебета
 * @property float $credit Сума кредита
 * @property string|null $description Опис операції
 * @property string|null $document_at Дата документа
 * @property string|null $created_at Дата створення
 */
class AccountingEntries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'accounting_entries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['number', 'debit', 'credit'], 'required'],
            [['debit', 'credit'], 'number'],
            [['document_at', 'created_at'], 'safe'],
            [['number', 'counterparty', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Номер документа',
            'counterparty' => 'Кореспондент',
//            'debit' => 'Сума дебета (Нарахування)', // Начисляем, что клиент должен
            'debit' => 'Нарахування', // Начисляем, что клиент должен
            'credit' => 'Приход', // Пока нет поступления денег
//            'credit' => 'Сума кредита (Приход)', // Пока нет поступления денег
            'description' => 'Опис операції',
            'document_at' => 'Дата документа',
            'created_at' => 'Дата створення',
        ];
    }

    public function getCounterparty()
    {
        return [
            'ТОВ "ІНГСОТ"' => 'ТОВ "ІНГСОТ"',
            'КРИВОШЕЙ ІГОР ОЛЕКСІЙОВИЧ' => 'КРИВОШЕЙ ІГОР ОЛЕКСІЙОВИЧ',
        ];
    }
}

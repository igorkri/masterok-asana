<?php

namespace common\models\report;

use Yii;

/**
 * This is the model class for table "payer".
 *
 * @property int $id
 * @property string $name Назва
 * @property string|null $email Електронна пошта
 * @property string|null $phone Телефон
 * @property string|null $contract Договір
 * @property string|null $director Директор ПІБ
 * @property string|null $director_case ПІБ директора в родовому відмінку
 * @property string|null $requisites Реквізити
 * @property string $created_at Дата створення
 */
class Payer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['requisites'], 'string'],
            [['created_at'], 'safe'],
            [['name', 'email', 'phone', 'contract', 'director', 'director_case'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['email'], 'unique'],
            [['phone'], 'unique'],
            [['contract'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Назва',
            'email' => 'Електронна пошта',
            'phone' => 'Телефон',
            'contract' => 'Договір',
            'director' => 'Директор ПІБ',
            'director_case' => 'ПІБ директора в родовому відмінку',
            'requisites' => 'Реквізити',
            'created_at' => 'Дата створення',
        ];
    }
}

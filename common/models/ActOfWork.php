<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "act_of_work".
 *
 * @property int $id
 * @property string $number Номер акту
 * @property string $status Статус акту
 * @property string $period Період виконання робіт
 * @property int $user_id ID користувача
 * @property string $date Дата складання акту
 * @property string $description Опис робіт
 * @property float $total_amount Загальна сума
 * @property float $paid_amount Сума, вже сплачена
 * @property string|null $file_excel Файл Excel
 * @property string|null $created_at Дата створення
 * @property string|null $updated_at Дата оновлення
 *
 * @property ActOfWorkDetail[] $actOfWorkDetails
 * @property User $user
 */
class ActOfWork extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'act_of_work';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['number', 'period', 'user_id', 'date', 'description', 'total_amount'], 'required'],
            [['user_id'], 'integer'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['description'], 'string'],
            [['total_amount', 'paid_amount'], 'number'],
            [['number', 'period'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 20],
            [['file_excel'], 'string', 'max' => 255],
            [['number'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Номер акту',
            'status' => 'Статус акту',
            'period' => 'Період виконання робіт',
            'user_id' => 'ID користувача',
            'date' => 'Дата складання акту',
            'description' => 'Опис робіт',
            'total_amount' => 'Загальна сума',
            'paid_amount' => 'Сума, вже сплачена',
            'file_excel' => 'Файл Excel',
            'created_at' => 'Дата створення',
            'updated_at' => 'Дата оновлення',
        ];
    }

    /**
     * Gets query for [[ActOfWorkDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActOfWorkDetails()
    {
        return $this->hasMany(ActOfWorkDetail::class, ['act_of_work_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}

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

    const STATUS_PENDING = 'pending'; // Очікує
    const STATUS_IN_PROGRESS = 'in_progress'; // В процесі
    const STATUS_PAID = 'paid'; // Оплачено
    const STATUS_PARTIALLY_PAID = 'partially_paid'; // Частково оплачено
    const STATUS_CANCELLED = 'cancelled'; // Скасовано
    const STATUS_ARCHIVED = 'archived'; // Архівовано
    const STATUS_DRAFT = 'draft'; // Чернетка

    /**
     * @var mixed|null
     */
    public static mixed $statusList = [
        self::STATUS_PENDING => 'Очікує',
        self::STATUS_IN_PROGRESS => 'В процесі',
        self::STATUS_PAID => 'Оплачено',
        self::STATUS_PARTIALLY_PAID => 'Частково оплачено',
        self::STATUS_CANCELLED => 'Скасовано',
        self::STATUS_ARCHIVED => 'Архівовано',
        self::STATUS_DRAFT => 'Чернетка',
    ];

    /**
     * @var mixed|null
     */
    public static mixed $monthsList = [
        "January" => "Січень",
        "February" => "Лютий",
        "March" => "Березень",
        "April" => "Квітень",
        "May" => "Травень",
        "June" => "Червень",
        "July" => "Липень",
        "August" => "Серпень",
        "September" => "Вересень",
        "October" => "Жовтень",
        "November" => "Листопад",
        "December" => "Грудень",
    ];
    /**
     * @var mixed|null
     */
    public static mixed $periodTypeList = [
        // перша половина місяця, друга половина місяця, тиждень, місяць, рік
        'year' => 'Рік',
        'first_half_month' => 'Перша половина місяця',
        'second_half_month' => 'Друга половина місяця',
        'month' => 'Місяць',
        'week' => 'Тиждень',
        'day' => 'День',
    ];

    /**
     * @var mixed|null
     *

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'act_of_work';
    }

    public static function generateNumber(): string
    {
        return (string)time();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['number', 'period', 'user_id', 'date', 'total_amount'], 'required'],
            [['user_id'], 'integer'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['description'], 'string'],
            [['total_amount', 'paid_amount'], 'number'],
            [['number'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 20],
            [['file_excel'], 'string', 'max' => 255],
            [['number'], 'unique'],
            [['period'], 'safe'],
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

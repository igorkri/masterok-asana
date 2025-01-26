<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "timer".
 *
 * @property int $id
 * @property string $task_gid
 * @property string $time
 * @property int $minute
 * @property float $coefficient
 * @property string|null $comment
 * @property int $status
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Task $taskG
 */
class Timer extends \yii\db\ActiveRecord
{

    /**
     * Статусы
     *
     * @var int
     *
     * Чекає на звіт
     * В процесі
     * Заплановано
     * Рахунок виставлено
     * Оплачено
     * Потребує уточнення
     */
    const STATUS_WAIT = 0;
    const STATUS_PROCESS = 1;
    const STATUS_PLANNED = 2;
    const STATUS_INVOICE = 3;
    const STATUS_PAID = 4;
    const STATUS_NEED_CLARIFICATION = 5;

    // Coefficient list
    static public array $coefficientList = [
        '0.0' => '0',
        '0.25' => '0.25',
        '0.5' => '0.5',
        '1.0' => '1.0',
        '1.5' => '1.5',
        '2.0' => '2.0',
        '2.5' => '2.5',
    ];

    static public array $statusList = [
        self::STATUS_WAIT => 'Чекає на звіт',
        self::STATUS_PROCESS => 'В процесі',
        self::STATUS_PLANNED => 'Заплановано',
        self::STATUS_INVOICE => 'Рахунок виставлено',
        self::STATUS_PAID => 'Оплачено',
        self::STATUS_NEED_CLARIFICATION => 'Потребує уточнення',
    ];


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'timer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_gid', 'time', 'minute', 'coefficient'], 'required'],
            [['time', 'created_at', 'updated_at'], 'safe'],
            [['minute', 'status'], 'integer'],
            [['coefficient'], 'number'],
            [['comment'], 'string'],
            [['task_gid'], 'string', 'max' => 255],
            [['task_gid'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_gid' => 'gid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_gid' => 'Задача',
            'time' => 'Час',
            'minute' => 'Хвилини',
            'coefficient' => 'Коефіцієнт',
            'comment' => 'Коментар',
            'status' => 'Статус',
            'created_at' => 'Дата створення',
            'updated_at' => 'Дата оновлення',
        ];
    }

    /**
     * Gets query for [[TaskG]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskG()
    {
        return $this->hasOne(Task::class, ['gid' => 'task_gid']);
    }

    public function getStatusText(int $status)
    {
        return self::$statusList[$status];
    }
}

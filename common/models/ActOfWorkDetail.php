<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "act_of_work_detail".
 *
 * @property int $id
 * @property int $act_of_work_id ID акту ремонту
 * @property int $time_id ID часу
 * @property int $task_gid ID завдання
 * @property int $project_gid ID проекту
 * @property string|null $project Проект
 * @property string|null $task Завдання
 * @property string|null $description Опис
 * @property float $amount Сума
 * @property float $hours Години
 * @property string|null $created_at Дата створення
 * @property string|null $updated_at Дата оновлення
 *
 * @property ActOfWork $actOfWork
 * @property Project $project0
 * @property Task $task0
 * @property Timer $time
 */
class ActOfWorkDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'act_of_work_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['act_of_work_id', 'time_id', 'task_gid', 'project_gid', 'amount', 'hours'], 'required'],
            [['act_of_work_id', 'time_id', 'task_gid', 'project_gid'], 'integer'],
            [['description'], 'string'],
            [['amount', 'hours'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['project', 'task'], 'string', 'max' => 255],
            [['act_of_work_id'], 'exist', 'skipOnError' => true, 'targetClass' => ActOfWork::class, 'targetAttribute' => ['act_of_work_id' => 'id']],
            [['project_gid'], 'exist', 'skipOnError' => true, 'targetClass' => Project::class, 'targetAttribute' => ['project_gid' => 'id']],
            [['task_gid'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_gid' => 'id']],
            [['time_id'], 'exist', 'skipOnError' => true, 'targetClass' => Timer::class, 'targetAttribute' => ['time_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'act_of_work_id' => 'ID акту ремонту',
            'time_id' => 'ID часу',
            'task_gid' => 'ID завдання',
            'project_gid' => 'ID проекту',
            'project' => 'Проект',
            'task' => 'Завдання',
            'description' => 'Опис',
            'amount' => 'Сума',
            'hours' => 'Години',
            'created_at' => 'Дата створення',
            'updated_at' => 'Дата оновлення',
        ];
    }

    /**
     * Gets query for [[ActOfWork]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActOfWork()
    {
        return $this->hasOne(ActOfWork::class, ['id' => 'act_of_work_id']);
    }

    /**
     * Gets query for [[Project0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProject0()
    {
        return $this->hasOne(Project::class, ['id' => 'project_gid']);
    }

    /**
     * Gets query for [[Task0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask0()
    {
        return $this->hasOne(Task::class, ['id' => 'task_gid']);
    }

    /**
     * Gets query for [[Time]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTime()
    {
        return $this->hasOne(Timer::class, ['id' => 'time_id']);
    }
}

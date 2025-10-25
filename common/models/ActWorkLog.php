<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "act_work_log".
 *
 * @property int $id
 * @property int $act_of_work_id ID акта
 * @property int $act_of_work_detail_id ID акта деталей
 * @property int $timer_id ID таймера
 * @property int $task_id ID задачи
 * @property int $project_id ID проекта
 * @property string|null $message Сообщение
 */
class ActWorkLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'act_work_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['act_of_work_id', 'act_of_work_detail_id', 'timer_id', 'task_id', 'project_id'], 'required'],
            [['act_of_work_id', 'act_of_work_detail_id', 'timer_id', 'task_id', 'project_id'], 'integer'],
            [['message'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'act_of_work_id' => 'ID акта',
            'act_of_work_detail_id' => 'ID акта деталей',
            'timer_id' => 'ID таймера',
            'task_id' => 'ID задачи',
            'project_id' => 'ID проекта',
            'message' => 'Сообщение',
        ];
    }
}

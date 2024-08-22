<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "asana_task".
 *
 * @property int $id
 * @property int $gid Задача
 * @property int|null $project_gid Проєкт
 * @property int|null $assignee_gid Виконавець
 * @property int|null $status_gid Пріоритет
 * @property int|null $priority_gid Пріоритет
 * @property int|null $type_gid Тип задачі
 * @property string|null $name Назва
 * @property string|null $notes Опис
 * @property int|null $completed Виконано
 * @property string|null $permalink_url Посилання на задачу
 * @property int|null $workspace_gid Роботодавець
 * @property string|null $followers Послідовники
 * @property string|null $created_at Created At
 * @property string|null $modified_at Modified At
 * @property string|null $updated_at Updated At
 */
class AsanaTask extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asana_task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gid'], 'required'],
            [['gid', 'project_gid', 'assignee_gid', 'status_gid', 'priority_gid', 'type_gid', 'completed', 'workspace_gid'], 'integer'],
            [['notes'], 'string'],
            [['followers', 'created_at', 'modified_at', 'updated_at'], 'safe'],
            [['name', 'permalink_url'], 'string', 'max' => 1555],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gid' => 'Задача',
            'project_gid' => 'Проєкт',
            'assignee_gid' => 'Виконавець',
            'status_gid' => 'Пріоритет',
            'priority_gid' => 'Пріоритет',
            'type_gid' => 'Тип задачі',
            'name' => 'Назва',
            'notes' => 'Опис',
            'completed' => 'Виконано',
            'permalink_url' => 'Посилання на задачу',
            'workspace_gid' => 'Роботодавець',
            'followers' => 'Послідовники',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'updated_at' => 'Updated At',
        ];
    }
}

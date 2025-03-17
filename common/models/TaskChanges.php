<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "task_changes".
 *
 * @property int $id
 * @property string $task_gid
 * @property string $field
 * @property string|null $old_value
 * @property string|null $new_value
 * @property string $changed_at
 *
 * @property Task $taskG
 */
class TaskChanges extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_changes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_gid', 'field', 'changed_at'], 'required'],
            [['old_value', 'new_value'], 'string'],
            [['changed_at'], 'safe'],
            [['task_gid', 'field'], 'string', 'max' => 255],
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
            'task_gid' => 'Task Gid',
            'field' => 'Field',
            'old_value' => 'Old Value',
            'new_value' => 'New Value',
            'changed_at' => 'Changed At',
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
}

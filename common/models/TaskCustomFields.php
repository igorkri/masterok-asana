<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "task_custom_fields".
 *
 * @property int $id
 * @property string $task_gid
 * @property string $custom_field_gid
 * @property string $name
 * @property string $type
 * @property string|null $display_value
 * @property string|null $enum_option_gid
 * @property string|null $enum_option_name
 * @property float|null $number_value
 *
 * @property Task $taskG
 */
class TaskCustomFields extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_custom_fields';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_gid', 'custom_field_gid', 'name', 'type'], 'required'],
            [['number_value'], 'number'],
            [['task_gid', 'custom_field_gid', 'name', 'type', 'display_value', 'enum_option_gid', 'enum_option_name'], 'string', 'max' => 255],
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
            'custom_field_gid' => 'Custom Field Gid',
            'name' => 'Name',
            'type' => 'Type',
            'display_value' => 'Display Value',
            'enum_option_gid' => 'Enum Option Gid',
            'enum_option_name' => 'Enum Option Name',
            'number_value' => 'Number Value',
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

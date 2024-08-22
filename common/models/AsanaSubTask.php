<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "asana_sub_task".
 *
 * @property int $id
 * @property int $gid Суб таск ID
 * @property int|null $task_gid Таск ID
 * @property int|null $complete Виконано
 * @property string|null $name Назва
 * @property string|null $note Опис
 */
class AsanaSubTask extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asana_sub_task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gid'], 'required'],
            [['gid', 'task_gid', 'complete'], 'integer'],
            [['note'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gid' => 'Суб таск ID',
            'task_gid' => 'Таск ID',
            'complete' => 'Виконано',
            'name' => 'Назва',
            'note' => 'Опис',
        ];
    }
}

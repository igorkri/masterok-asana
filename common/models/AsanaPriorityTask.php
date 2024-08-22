<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "asana_priority_task".
 *
 * @property int $id
 * @property int $project_gid Проєкт ID
 * @property string|null $project_name Проєкт
 * @property int $status_gid Статус ID
 * @property string|null $status_name Статус
 * @property string|null $color Колір лейби
 */
class AsanaPriorityTask extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asana_priority_task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_gid', 'status_gid'], 'required'],
            [['project_gid', 'status_gid'], 'integer'],
            [['project_name', 'status_name', 'color'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_gid' => 'Проєкт ID',
            'project_name' => 'Проєкт',
            'status_gid' => 'Статус ID',
            'status_name' => 'Статус',
            'color' => 'Колір лейби',
        ];
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property string $name
 * @property int $gid
 * @property int $workspace_gid
 * @property string $resource_type
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'gid', 'workspace_gid', 'resource_type'], 'required'],
            [['gid', 'workspace_gid'], 'integer'],
            [['name', 'resource_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Назва проєкту',
            'gid' => 'Gid',
            'workspace_gid' => 'Workspace Gid',
            'resource_type' => 'Resource Type',
        ];
    }

    public function getTaskQty()
    {
        return $this->hasMany(Task::class, ['project_gid' => 'gid'])->count();
    }

    public function getTasks()
    {
        return $this->hasMany(Task::class, ['gid' => 'project_gid']);
    }

    public function getWorkspace()
    {
        return $this->hasOne(Workspace::class, ['gid' => 'workspace_gid']);
    }


    /**
     * Задачі які потребують виконання
     */
    public function getTaskExecution()
    {
        return $this->hasMany(Task::class, ['project_gid' => 'gid'])
            ->where(['section_project_name' => 'До роботи'])->count();
    }


    /**
     * Назва проєкту
     */
    public function getName()
    {
        return $this->name;
    }

}

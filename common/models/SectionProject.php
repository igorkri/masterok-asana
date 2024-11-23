<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "section_project".
 *
 * @property int $id
 * @property int $gid
 * @property string $name
 * @property int $project_gid
 * @property string|null $resource_type
 *
 * @property Project $projectG
 */
class SectionProject extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'section_project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gid', 'name', 'project_gid'], 'required'],
            [['gid', 'project_gid'], 'integer'],
            [['name', 'resource_type'], 'string', 'max' => 255],
            [['project_gid'], 'exist', 'skipOnError' => true, 'targetClass' => Project::class, 'targetAttribute' => ['project_gid' => 'gid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gid' => 'Gid',
            'name' => 'Name',
            'project_gid' => 'Project Gid',
            'resource_type' => 'Resource Type',
        ];
    }

    /**
     * Gets query for [[ProjectG]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjectG()
    {
        return $this->hasOne(Project::class, ['gid' => 'project_gid']);
    }
}

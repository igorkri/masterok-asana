<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "project_custom_fields".
 *
 * @property int $id
 * @property string $gid
 * @property string $project_gid
 * @property string $name
 * @property string $type
 * @property string|null $resource_type
 * @property string|null $resource_subtype
 * @property int|null $is_important
 *
 * @property ProjectCustomFieldEnumOptions[] $projectCustomFieldEnumOptions
 */
class ProjectCustomFields extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project_custom_fields';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gid', 'project_gid', 'name', 'type'], 'required'],
            [['is_important'], 'integer'],
            [['gid', 'project_gid', 'name', 'type', 'resource_type', 'resource_subtype'], 'string', 'max' => 255],
//            [['gid'], 'unique'],
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
            'project_gid' => 'Project Gid',
            'name' => 'Name',
            'type' => 'Type',
            'resource_type' => 'Resource Type',
            'resource_subtype' => 'Resource Subtype',
            'is_important' => 'Is Important',
        ];
    }

    /**
     * Gets query for [[ProjectCustomFieldEnumOptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjectCustomFieldEnumOptions()
    {
        return $this->hasMany(ProjectCustomFieldEnumOptions::class, ['custom_field_gid' => 'gid']);
    }
}

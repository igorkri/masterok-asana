<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "project_custom_field_enum_options".
 *
 * @property int $id
 * @property string $custom_field_gid
 * @property string $gid
 * @property string $name
 * @property string|null $color
 * @property int|null $enabled
 * @property string|null $resource_type
 *
 * @property ProjectCustomFields $customFieldG
 */
class ProjectCustomFieldEnumOptions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project_custom_field_enum_options';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['custom_field_gid', 'gid', 'name'], 'required'],
            [['enabled'], 'integer'],
            [['custom_field_gid', 'gid', 'name', 'color', 'resource_type'], 'string', 'max' => 255],
            [['gid'], 'unique'],
            [['custom_field_gid'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectCustomFields::class, 'targetAttribute' => ['custom_field_gid' => 'gid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'custom_field_gid' => 'Custom Field Gid',
            'gid' => 'Gid',
            'name' => 'Name',
            'color' => 'Color',
            'enabled' => 'Enabled',
            'resource_type' => 'Resource Type',
        ];
    }

    /**
     * Gets query for [[CustomFieldG]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomFieldG()
    {
        return $this->hasOne(ProjectCustomFields::class, ['gid' => 'custom_field_gid']);
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "asana_users".
 *
 * @property int $id
 * @property string $gid
 * @property string $name
 * @property string|null $resource_type
 */
class AsanaUsers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asana_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gid', 'name'], 'required'],
            [['gid', 'name', 'resource_type'], 'string', 'max' => 255],
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
            'resource_type' => 'Resource Type',
        ];
    }
}

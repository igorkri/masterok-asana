<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "asana_project".
 *
 * @property int $id
 * @property int|null $gid Project ID
 * @property string|null $name Назва проєкту
 *
 * @property AsanaTask[] $asanaTasks
 */
class AsanaProject extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asana_project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gid'], 'integer'],
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
            'gid' => 'Project ID',
            'name' => 'Назва проєкту',
        ];
    }

    /**
     * Gets query for [[AsanaTasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAsanaTasks()
    {
        return $this->hasMany(AsanaTask::class, ['project_gid' => 'id']);
    }
}

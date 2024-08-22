<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "asana_users".
 *
 * @property int $id
 * @property int|null $gid User ID
 * @property string|null $name Ім\`я
 * @property string|null $email email
 *
 * @property AsanaTask[] $asanaTasks
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
            [['gid'], 'integer'],
            [['name', 'email'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gid' => 'User ID',
            'name' => 'Ім\\`я',
            'email' => 'email',
        ];
    }

    /**
     * Gets query for [[AsanaTasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAsanaTasks()
    {
        return $this->hasMany(AsanaTask::class, ['assignee_gid' => 'id']);
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%asana_users}}`.
 */
class m241026_203023_create_asana_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%asana_users}}', [
            'id' => $this->primaryKey(),
            'gid' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'resource_type' => $this->string(),
        ]);

        $this->createIndex(
            'idx-asana_users-gid',
            '{{%asana_users}}',
            'gid'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-asana_users-gid',
            '{{%asana_users}}'
        );
        $this->dropTable('{{%asana_users}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%project}}`.
 */
class m241026_185725_create_project_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%project}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'gid' => $this->bigInteger()->notNull(),
            'workspace_gid' => $this->bigInteger()->notNull(),
            'resource_type' => $this->string(255)->notNull(),
        ]);

        $this->createIndex(
            'idx-project-gid',
            'project',
            'gid'
        );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropIndex(
            'idx-project-gid',
            'project'
        );

        $this->dropTable('{{%project}}');
    }
}

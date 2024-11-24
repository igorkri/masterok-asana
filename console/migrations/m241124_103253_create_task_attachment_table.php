<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_attachment}}`.
 */
class m241124_103253_create_task_attachment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task_attachment}}', [
            'id' => $this->primaryKey(),
            'task_gid' => $this->string()->null(),
            'gid' => $this->string()->null(),
            'created_at' => $this->dateTime()->null(),
            'download_url' => $this->text()->null(),
            'name' => $this->string()->null(),
            'parent_gid' => $this->string()->null(),
            'parent_name' => $this->string()->null(),
            'parent_resource_type' => $this->string()->null(),
            'parent_resource_subtype' => $this->string()->null(),
            'permanent_url' => $this->text()->null(),
            'resource_type' => $this->string()->null(),
            'resource_subtype' => $this->string()->null(),
            'view_url' => $this->text()->null(),
        ]);

        $this->createIndex('idx-task_attachment-task_gid', '{{%task_attachment}}', 'task_gid');
        $this->addForeignKey('fk-task_attachment-task_gid', '{{%task_attachment}}', 'task_gid', '{{%task}}', 'gid', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-task_attachment-task_gid', '{{%task_attachment}}');
        $this->dropIndex('idx-task_attachment-task_gid', '{{%task_attachment}}');

        $this->dropTable('{{%task_attachment}}');
    }
}

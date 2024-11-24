<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_story}}`.
 */
class m241124_084438_create_task_story_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Создание таблицы task_story для хранения комментариев и действий
        $this->createTable('{{%task_story}}', [
            'id' => $this->primaryKey(),
            'gid' => $this->string()->null(),
            'task_gid' => $this->string()->null(),
            'created_at' => $this->dateTime()->null(),
            'created_by_gid' => $this->string()->null(),
            'created_by_name' => $this->string()->null(),
            'created_by_resource_type' => $this->string()->null(),
            'story_gid' => $this->string()->null(),
            'resource_type' => $this->string()->null(),
            'text' => $this->text()->null(),
            'resource_subtype' => $this->string()->null(),
        ]);

        // Добавление индекса и внешнего ключа для task_gid
        $this->createIndex('idx-task_story-task_gid', '{{%task_story}}', 'task_gid');
        $this->addForeignKey('fk-task_story-task_gid', '{{%task_story}}', 'task_gid', '{{%task}}', 'gid', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаление внешнего ключа и индекса для task_gid
        $this->dropForeignKey('fk-task_story-task_gid', '{{%task_story}}');
        $this->dropIndex('idx-task_story-task_gid', '{{%task_story}}');

        // Удаление таблицы task_story
        $this->dropTable('{{%task_story}}');
    }
}

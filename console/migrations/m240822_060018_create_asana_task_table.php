<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%asana_task}}`.
 * Has foreign keys to the tables:
 *
 * - `asana_project`
 * - `asana_users`
 */
class m240822_060018_create_asana_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%asana_task}}', [
            'id' => $this->primaryKey(),
            'gid' => $this->bigInteger()->notNull()->comment('Задача'),
            'project_gid' => $this->bigInteger()->comment('Проєкт'),
            'assignee_gid' => $this->bigInteger()->comment('Виконавець'),
            'status_gid' => $this->bigInteger()->comment('Пріоритет'),
            'priority_gid' => $this->bigInteger()->comment('Пріоритет'),
            'type_gid' => $this->bigInteger()->comment('Тип задачі'),
            'name' => $this->string(1555)->comment('Назва'),
            'notes' => $this->text()->comment('Опис'),
            'completed' => $this->integer()->comment('Виконано'),
            'permalink_url' => $this->string()->comment('Посилання на задачу'),
            'workspace_gid' =>  $this->bigInteger()->comment('Роботодавець'),
            'followers' =>  $this->json()->comment('Послідовники'),
            'created_at' => $this->timestamp()->comment('Created At'),
            'modified_at' => $this->timestamp()->comment('Modified At'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Updated At'),
        ]);

        // Creates index for column `gid`
        $this->createIndex(
            '{{%idx-asana_task-gid}}',
            '{{%asana_task}}',
            'gid'
        );

        // Add foreign key for table `asana_project`
//        $this->addForeignKey(
//            '{{%fk-asana_task-project_gid}}',
//            '{{%asana_task}}',
//            'project_gid',
//            '{{%asana_project}}',
//            'gid',
//            'SET NULL'
//        );

        // Add foreign key for table `asana_users`
//        $this->addForeignKey(
//            '{{%fk-asana_task-assignee_gid}}',
//            '{{%asana_task}}',
//            'assignee_gid',
//            '{{%asana_users}}',
//            'gid',
//            'SET NULL'
//        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drops foreign key for table `asana_project`
//        $this->dropForeignKey(
//            '{{%fk-asana_task-project_gid}}',
//            '{{%asana_task}}'
//        );
//
//        // Drops foreign key for table `asana_users`
//        $this->dropForeignKey(
//            '{{%fk-asana_task-assignee_gid}}',
//            '{{%asana_task}}'
//        );

        // Drops index for column `gid`
        $this->dropIndex(
            '{{%idx-asana_task-gid}}',
            '{{%asana_task}}'
        );

        $this->dropTable('{{%asana_task}}');
    }
}

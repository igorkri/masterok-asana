<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%asana_sub_task}}`.
 * Has foreign keys to the tables:
 *
 * - `asana_task`
 */
class m240822_072201_create_asana_sub_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%asana_sub_task}}', [
            'id' => $this->primaryKey(),
            'gid' => $this->bigInteger()->notNull()->comment('Суб таск ID'),
            'task_gid' => $this->bigInteger()->comment('Таск ID'),
            'complete' => $this->integer(1)->comment('Виконано'),
            'name' => $this->string()->comment('Назва'),
            'note' => $this->text()->comment('Опис')
        ]);

        // creates index for column `gid`
        $this->createIndex(
            '{{%idx-asana_sub_task-gid}}',
            '{{%asana_sub_task}}',
            'gid'
        );

        // creates index for column `task_gid`
        $this->createIndex(
            '{{%idx-asana_sub_task-task_gid}}',
            '{{%asana_sub_task}}',
            'task_gid'
        );

        // add foreign key for table `asana_task`
//        $this->addForeignKey(
//            '{{%fk-asana_sub_task-task_gid}}',
//            '{{%asana_sub_task}}',
//            'task_gid',
//            '{{%asana_task}}',  // изменено с `asana_tasks` на `asana_task`
//            'gid',
//            'SET NULL'
//        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `asana_task`
//        $this->dropForeignKey(
//            '{{%fk-asana_sub_task-task_gid}}',
//            '{{%asana_sub_task}}'
//        );

        // drops index for column `gid`
        $this->dropIndex(
            '{{%idx-asana_sub_task-gid}}',
            '{{%asana_sub_task}}'
        );

        // drops index for column `task_gid`
        $this->dropIndex(
            '{{%idx-asana_sub_task-task_gid}}',
            '{{%asana_sub_task}}'
        );

        $this->dropTable('{{%asana_sub_task}}');
    }
}

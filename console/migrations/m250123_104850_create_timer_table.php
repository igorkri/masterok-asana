<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%timer}}`.
 */
class m250123_104850_create_timer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%timer}}', [
            'id' => $this->primaryKey(),
            'task_gid' => $this->string()->notNull(),
            'time' => $this->time()->notNull(),
            'minute' => $this->integer()->notNull(),
            'coefficient' => $this->float()->notNull(),
            'comment' => $this->text(),
            'status' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),

        ]);

        $this->createIndex(
            'idx-timer-task_gid',
            'timer',
            'task_gid'
        );

        $this->addForeignKey(
            'fk-timer-task_gid',
            'timer',
            'task_gid',
            'task',
            'gid',
            'CASCADE'
        );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey(
            'fk-timer-task_gid',
            'timer'
        );

        $this->dropIndex(
            'idx-timer-task_gid',
            'timer'
        );

        $this->dropTable('{{%timer}}');
    }
}

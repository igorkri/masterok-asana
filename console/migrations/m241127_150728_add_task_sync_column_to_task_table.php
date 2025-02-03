<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%task}}`.
 */
class m241127_150728_add_task_sync_column_to_task_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%task}}', 'task_sync', $this->string()->null()->comment('Task Sync'));
        $this->addColumn('{{%task}}', 'task_sync_in', $this->dateTime()->comment('Task Sync In'));
        $this->addColumn('{{%task}}', 'task_sync_out', $this->dateTime()->comment('Task Sync Out'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%task}}', 'task_sync');
        $this->dropColumn('{{%task}}', 'task_sync_in');
        $this->dropColumn('{{%task}}', 'task_sync_out');
    }
}

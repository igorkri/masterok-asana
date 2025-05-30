<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%timer}}`.
 */
class m250530_080007_add_archive_column_to_timer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%timer}}', 'archive', $this->tinyInteger()->defaultValue(0)->after('status'));
        $this->createIndex('idx-timer-archived', '{{%timer}}', 'archive');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-timer-archived', '{{%timer}}');
        $this->dropColumn('{{%timer}}', 'archive');
    }
}

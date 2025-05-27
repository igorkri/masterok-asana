<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%timer}}`.
 */
class m250527_193325_add_status_act_column_to_timer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%timer}}', 'status_act', $this->string(50)->null()->defaultValue('not_ok')->after('status')->comment('Статус акту виконаних робіт'));
        $this->createIndex('idx-timer-status_act', '{{%timer}}', 'status_act');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-timer-status_act', '{{%timer}}');
        $this->dropColumn('{{%timer}}', 'status_act');
    }
}

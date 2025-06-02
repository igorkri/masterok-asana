<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%act_of_work}}`.
 */
class m250602_152912_add_telegram_column_to_act_of_work_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%act_of_work}}', 'telegram_status', $this->string()->defaultValue('pending')->comment('Telegram status'));
        $this->addColumn('{{%act_of_work}}', 'type', $this->string()->defaultValue('')->comment('Тип запису(акт, надходження, новий проєкт)'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%act_of_work}}', 'telegram_status');
        $this->dropColumn('{{%act_of_work}}', 'type');
    }
}

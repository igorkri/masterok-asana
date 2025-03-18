<?php

use yii\db\Migration;

/**
 * Class m250318_072914_add_column_date_invoice_to_timer_table
 */
class m250318_072914_add_column_date_invoice_to_timer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%timer}}', 'date_invoice', $this->date()->null()->comment('Дата виставлення рахунку'));
        $this->addColumn('{{%timer}}', 'date_report', $this->string(100)->null()->comment('Дата звіту'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%timer}}', 'date_invoice');
        $this->dropColumn('{{%timer}}', 'date_report');
    }
}

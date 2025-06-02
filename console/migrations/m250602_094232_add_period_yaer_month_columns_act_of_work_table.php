<?php

use yii\db\Migration;

/**
 * Class m250602_094232_add_period_yaer_month_columns_act_of_work_table
 */
class m250602_094232_add_period_yaer_month_columns_act_of_work_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%act_of_work}}', 'period_type', $this->string()->null()->after('period')->comment('Період тип'));
        $this->addColumn('{{%act_of_work}}', 'period_year', $this->string()->null()->after('period_type')->comment('Рік періоду'));
        $this->addColumn('{{%act_of_work}}', 'period_month', $this->string()->null()->after('period_year')->comment('Місяць періоду'));

        // убераем поле period из обязательных полей
        $this->alterColumn('{{%act_of_work}}', 'period', $this->string()->null()->comment('Період'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%act_of_work}}', 'period_type');
        $this->dropColumn('{{%act_of_work}}', 'period_year');
        $this->dropColumn('{{%act_of_work}}', 'period_month');
    }

}

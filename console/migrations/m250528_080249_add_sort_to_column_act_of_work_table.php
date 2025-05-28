<?php

use yii\db\Migration;

/**
 * Class m250528_080249_add_sort_to_column_act_of_work_table
 */
class m250528_080249_add_sort_to_column_act_of_work_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%act_of_work}}', 'sort', $this->integer()->notNull()->defaultValue(0)->comment('Порядок сортування'));
        $this->createIndex('idx-act_of_work-sort', '{{%act_of_work}}', 'sort');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-act_of_work-sort', '{{%act_of_work}}');
        $this->dropColumn('{{%act_of_work}}', 'sort');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250528_080249_add_sort_to_column_act_of_work_table cannot be reverted.\n";

        return false;
    }
    */
}

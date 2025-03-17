<?php

use yii\db\Migration;

/**
 * Class m241123_213731_add_parent_gid_to_task_table
 */
class m241123_213731_add_parent_gid_to_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%task}}', 'parent_gid', $this->string(50)->null()->after('gid'));

        // Если нужно добавить внешний ключ, раскомментируйте следующие строки и измените 'related_table' и 'related_column' на соответствующие значения.
        // $this->addForeignKey(
        //     'fk-task-parent_gid',
        //     '{{%task}}',
        //     'parent_gid',
        //     '{{%related_table}}',
        //     'related_column',
        //     'SET NULL',
        //     'CASCADE'
        // );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Если добавляли внешний ключ, нужно также его удалить.
        // $this->dropForeignKey('fk-task-parent_gid', '{{%task}}');

        $this->dropColumn('{{%task}}', 'parent_gid');
    }

}

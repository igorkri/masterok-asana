<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%act_work_log}}`.
 */
class m250528_061408_create_act_work_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%act_work_log}}', [
            'id' => $this->primaryKey(),
            'act_of_work_id' => $this->integer()->notNull()->comment('ID акта'),
            'act_of_work_detail_id' => $this->integer()->notNull()->comment('ID акта деталей'),
            'timer_id' => $this->integer()->notNull()->comment('ID таймера'),
            'task_id' => $this->integer()->notNull()->comment('ID задачи'),
            'project_id' => $this->integer()->notNull()->comment('ID проекта'),
            'message' => $this->text()->null()->comment('Сообщение'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Дата створення'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('Дата оновлення'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%act_work_log}}');
    }
}

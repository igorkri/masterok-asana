<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%asana_status_task}}`.
 */
class m240822_072419_create_asana_status_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%asana_status_task}}', [
            'id' => $this->primaryKey(),
            'project_gid' => $this->bigInteger()->notNull()->comment('Проєкт ID'),
            'project_name' => $this->string()->comment('Проєкт'),
            'status_gid' => $this->bigInteger()->notNull()->comment('Статус ID'),
            'status_name' => $this->string()->comment('Статус'),
            'color' => $this->string()->comment('Колір лейби')
        ]);

        $this->createTable('{{%asana_priority_task}}', [
            'id' => $this->primaryKey(),
            'project_gid' => $this->bigInteger()->notNull()->comment('Проєкт ID'),
            'project_name' => $this->string()->comment('Проєкт'),
            'status_gid' => $this->bigInteger()->notNull()->comment('Статус ID'),
            'status_name' => $this->string()->comment('Статус'),
            'color' => $this->string()->comment('Колір лейби')
        ]);


        $this->createTable('{{%asana_type_task}}', [
            'id' => $this->primaryKey(),
            'project_gid' => $this->bigInteger()->notNull()->comment('Проєкт ID'),
            'project_name' => $this->string()->comment('Проєкт'),
            'status_gid' => $this->bigInteger()->notNull()->comment('Статус ID'),
            'status_name' => $this->string()->comment('Статус'),
            'color' => $this->string()->comment('Колір лейби')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%asana_status_task}}');
        $this->dropTable('{{%asana_priority_task}}');
        $this->dropTable('{{%asana_type_task}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%asana_project}}`.
 */
class m240822_060001_create_asana_project_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%asana_project}}', [
            'id' => $this->primaryKey(),
            'gid' => $this->bigInteger()->notNull()->comment('Project ID'),
            'name' => $this->string()->comment('Назва проєкту')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%asana_project}}');
    }
}

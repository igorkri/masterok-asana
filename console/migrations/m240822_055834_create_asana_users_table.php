<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%asana_users}}`.
 */
class m240822_055834_create_asana_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%asana_users}}', [
            'id' => $this->primaryKey(),
            'gid' => $this->bigInteger()->notNull()->comment('User ID'),
            'name' => $this->string()->comment('Ім\`я'),
            'email' => $this->string()->comment('email')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%asana_users}}');
    }
}

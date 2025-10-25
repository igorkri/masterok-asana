<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%accounting_entries}}`.
 */
class m250317_203840_create_accounting_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%accounting_entries}}', [
            'id' => $this->primaryKey(),
            'number' => $this->string(255)->null()->comment('Номер документа'),
            'counterparty' => $this->string()->null()->comment('Кориспондент'),
            'debit' => $this->decimal(10,2)->null()->comment('Сума дебета'),
            'credit' => $this->decimal(10,2)->null()->comment('Сума кредита'),
            'description' => $this->string(255)->null()->comment('Опис операції'),
            'document_at' => $this->date()->null()->comment('Дата документа'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Дата створення'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%accounting_entries}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%invoice}}`.
 */
class m250130_220339_create_invoice_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%invoice}}', [
            'id' => $this->primaryKey(),
            'payer_id' => $this->integer()->notNull(),
            'invoice_no' => $this->integer()->null(),
            'act_no' => $this->integer()->null(),
            'date_invoice' => $this->date()->null(),
            'date_act' => $this->date()->null(),
            'title_invoice' => $this->string()->null(),
            'title_act' => $this->string()->null(),
            'qty' => $this->integer()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Дата створення'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('Дата оновлення'),
        ]);

        $this->createIndex(
            'idx-invoice-payer_id',
            'invoice',
            'payer_id'
        );


        $this->addForeignKey(
            'fk-invoice-payer_id',
            'invoice',
            'payer_id',
            'payer',
            'id',
            'CASCADE'
        );

        $this->batchInsert('{{%invoice}}',
            ['id', 'payer_id', 'invoice_no', 'act_no', 'date_act', 'title_act', 'qty', 'amount', 'created_at', 'updated_at'],
            [
                [1, 22, 104, 104, '2023-06-02', 'Доопрацювання та оновлення веб-сайту iknet.com.ua', 1, 15000.00, '2023-06-02 00:00:00', '2023-06-02 00:00:00'],
                [2, 22, 105, 105, '2023-06-30', 'Доопрацювання та оновлення веб-сайтів yume-honda.com.ua', 1, 10000.00, '2023-06-30 00:00:00', '2023-06-30 00:00:00'],
                [3, 22, 106, 106, '2023-07-11', 'Доопрацювання та оновлення веб-сайтів levsha.com.ua', 1, 20800.00, '2023-07-11 00:00:00', '2023-07-11 00:00:00'],
                [4, 22, 107, 107, '2023-08-04', 'Доопрацювання веб сайтів paul-lange-ukraine.com', 1, 15000.00, '2023-08-04 00:00:00', '2023-08-04 00:00:00'],
                [5, 22, 108, 108, '2023-08-15', 'Доопрацювання web сайтів sixt.ua', 1, 48000.00, '2023-08-15 00:00:00', '2023-08-15 00:00:00'],
                [9, 22, 109, 109, '2023-11-10', 'За розробку веб-сайту revive and thrive', 1, 55000.00, '2023-11-10 00:00:00', '2023-11-10 00:00:00'],
                [25, 22, 10, 10, '2024-12-20', 'Розробка та підтримка веб-сайту wurmup.ua', 1, 55000.00, '2024-12-20 00:00:00', '2024-12-20 00:00:00'],
                [26, 22, 9, 6, '2024-12-02', 'Підтримка та доопрацювання веб-сайту yume-honda.com.ua', 1, 30000.00, '2024-12-02 00:00:00', '2024-12-02 00:00:00'],
                [27, 22, 8, 4, '2024-07-01', 'Підтримка та доопрацювання веб-сайту http://yume-honda.com.ua/ та levsha.com.ua', 1, 63000.00, '2024-07-01 00:00:00', '2024-07-01 00:00:00'],
                [28, 22, 7, 5, '2024-11-01', 'Підтримка та доопрацювання веб-сайту Sixt.ua', 1, 36000.00, '2024-11-01 00:00:00', '2024-11-01 00:00:00'],
                [29, 22, 6, 3, '2024-03-01', 'Підтримка та доопрацювання веб-сайту Sixt.ua', 1, 84800.00, '2024-03-01 00:00:00', '2024-03-01 00:00:00'],
                [30, 22, 5, 3, '2024-03-01', 'Підтримка та доопрацювання веб-сайту Sixt.ua', 1, 31321.00, '2024-03-01 00:00:00', '2024-03-01 00:00:00'],
                [31, 22, 3, 3, '2024-03-01', 'Підтримка та доопрацювання веб-сайту Sixt.ua', 1, 72500.00, '2024-03-01 00:00:00', '2024-03-01 00:00:00'],
                [32, 22, 4, 4, '2024-04-01', 'Підтримка та доопрацювання веб-сайту Levsha.com.ua, paul-lange-ukraine.com, sixt.ua', 1, 10000.00, '2024-04-01 00:00:00', '2024-04-01 00:00:00'],
                [33, 22, 2, 2, '2024-01-11', 'Доопрацювання веб-сайту Paul-Lange', 1, 15000.00, '2024-01-11 00:00:00', '2024-01-11 00:00:00'],
                [34, 22, 1, 1, '2024-01-11', 'Доопрацювання веб-сайту Sitechcom', 1, 15000.00, '2024-01-11 00:00:00', '2024-01-11 00:00:00'],
                [35, 22, 110, 1, '2023-12-01', 'Доопрацювання веб-сайту sixt.ua', 1, 11500.00, '2023-12-01 00:00:00', '2023-12-01 00:00:00'],
                [36, 22, 6, 4, '2024-07-01', 'Підтримку та доопрацювання веб-сайту http://yume-honda.com.ua/ та levsha.com.ua', 1, 18000.00, '2024-07-01 00:00:00', '2024-07-01 00:00:00'],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-invoice-payer_id',
            'invoice'
        );

        $this->dropIndex(
            'idx-invoice-payer_id',
            'invoice'
        );

        $this->dropTable('{{%invoice}}');
    }
}

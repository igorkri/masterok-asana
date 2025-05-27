<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%akt_repair}}`.
 */
class m250527_061430_create_akt_repair_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%act_of_work}}', [
            'id' => $this->primaryKey(),
            'number' => $this->string(50)->notNull()->unique()->comment('Номер акту'),
            'status' => $this->string(20)->notNull()->defaultValue('pending')->comment('Статус акту'),
            'period' => $this->json()->null()->comment('Період виконання робіт'),
            'user_id' => $this->integer()->notNull()->comment('ID користувача'),
            'date' => $this->date()->notNull()->comment('Дата складання акту'),
            'description' => $this->text()->null()->comment('Опис робіт'),
            'total_amount' => $this->decimal(10, 2)->notNull()->comment('Загальна сума'),
            'paid_amount' => $this->decimal(10, 2)->notNull()->defaultValue(0)->comment('Сума, вже сплачена'),
            'file_excel' => $this->string()->null()->comment('Файл Excel'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Дата створення'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('Дата оновлення'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->createTable('{{%act_of_work_detail}}', [
            'id' => $this->primaryKey(),
            'act_of_work_id' => $this->integer()->notNull()->comment('ID акту ремонту'),
            'time_id' => $this->integer()->null()->comment('ID часу'),
            'task_gid' => $this->string()->null()->comment('ID завдання'),
            'project_gid' => $this->string()->null()->comment('ID проекту'),
            'project' => $this->string(255)->null()->comment('Проект'),
            'task' => $this->string(255)->null()->comment('Завдання'),
            'description' => $this->text()->null()->comment('Опис'),
            'amount' => $this->decimal(10, 2)->notNull()->comment('Сума'),
            'hours' => $this->decimal(10, 2)->notNull()->comment('Години'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Дата створення'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('Дата оновлення'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

//        $this->addForeignKey(
//            'fk-act_of_work-user_id',
//            '{{%act_of_work}}',
//            'user_id',
//            '{{%user}}',
//            'id',
//            'CASCADE',
//            'CASCADE'
//        );
        $this->addForeignKey(
            'fk-act_of_work_detail-act_of_work_id',
            '{{%act_of_work_detail}}',
            'act_of_work_id',
            '{{%act_of_work}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
//        $this->addForeignKey(
//            'fk-act_of_work_detail-time_id',
//            '{{%act_of_work_detail}}',
//            'time_id',
//            '{{%timer}}',
//            'id',
//            'CASCADE',
//            'CASCADE'
//        );
//        $this->addForeignKey(
//            'fk-act_of_work_detail-task_id',
//            '{{%act_of_work_detail}}',
//            'task_id',
//            '{{%task}}',
//            'id',
//            'CASCADE',
//            'CASCADE'
//        );

//        $this->addForeignKey(
//            'fk-act_of_work_detail-project_id',
//            '{{%act_of_work_detail}}',
//            'project_id',
//            '{{%project}}',
//            'id',
//            'CASCADE',
//            'CASCADE'
//        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-act_of_work_detail-act_of_work_id', '{{%act_of_work_detail}}');
        $this->dropTable('{{%act_of_work_detail}}');
        $this->dropTable('{{%act_of_work}}');
    }

}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_changes}}`.
 */
class m241027_104801_create_task_changes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task_changes}}', [
            'id' => $this->primaryKey(),
            'task_gid' => $this->string()->notNull(),              // GID задачи из Asana
            'field' => $this->string()->notNull(),                 // Название поля, которое изменилось
            'old_value' => $this->text()->null(),                  // Старое значение поля
            'new_value' => $this->text()->null(),                  // Новое значение поля
            'changed_at' => $this->dateTime()->notNull(),          // Время, когда произошло изменение
        ]);

        // Индекс для ускорения поиска изменений по task_gid
        $this->createIndex(
            'idx-task_changes-task_gid',
            '{{%task_changes}}',
            'task_gid'
        );

        // Внешний ключ для связи с таблицей task
        $this->addForeignKey(
            'fk-task_changes-task_gid',
            '{{%task_changes}}',
            'task_gid',
            '{{%task}}',
            'gid',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаление внешнего ключа и индекса
        $this->dropForeignKey('fk-task_changes-task_gid', '{{%task_changes}}');
        $this->dropIndex('idx-task_changes-task_gid', '{{%task_changes}}');

        // Удаление таблицы
        $this->dropTable('{{%task_changes}}');
    }
}

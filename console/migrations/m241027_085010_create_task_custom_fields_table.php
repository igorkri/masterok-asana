<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_custom_fields}}`.
 */
class m241027_085010_create_task_custom_fields_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task_custom_fields}}', [
            'id' => $this->primaryKey(),
            'task_gid' => $this->string()->notNull(),                  // GID задачи из Asana
            'custom_field_gid' => $this->string()->notNull(),          // GID кастомного поля
            'name' => $this->string()->notNull(),                      // Название кастомного поля
            'type' => $this->string()->notNull(),                      // Тип кастомного поля (например, enum или number)
            'display_value' => $this->string()->null(),                // Отображаемое значение (например, "Новая функция")
            'enum_option_gid' => $this->string()->null(),              // GID выбранной опции, если поле типа enum
            'enum_option_name' => $this->string()->null(),             // Название выбранной опции, если поле типа enum
            'number_value' => $this->decimal(10, 2)->null(),           // Числовое значение, если поле типа number
        ]); // Кодировка utf8mb4 для поддержки специальных символов

        // Индекс для ускорения поиска кастомных полей по task_gid
        $this->createIndex(
            'idx-task_custom_fields-task_gid',
            '{{%task_custom_fields}}',
            'task_gid'
        );

        // Внешний ключ для связи с таблицей task
        $this->addForeignKey(
            'fk-task_custom_fields-task_gid',
            '{{%task_custom_fields}}',
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
        $this->dropForeignKey('fk-task_custom_fields-task_gid', '{{%task_custom_fields}}');
        $this->dropIndex('idx-task_custom_fields-task_gid', '{{%task_custom_fields}}');

        // Удаление таблицы
        $this->dropTable('{{%task_custom_fields}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%project_custom_fields}}`.
 */
class m241026_195710_create_project_custom_fields_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Создание таблицы для хранения информации о кастомных полях проекта
        $this->createTable('{{%project_custom_fields}}', [
            'id' => $this->primaryKey(),
            'gid' => $this->string()->notNull(),
            'project_gid' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'type' => $this->string()->notNull(),
            'resource_type' => $this->string(),
            'resource_subtype' => $this->string(),
            'is_important' => $this->boolean()->defaultValue(false),
        ]);

        // Индексы для полей gid и project_gid
        $this->createIndex(
            'idx-project_custom_fields-gid',
            '{{%project_custom_fields}}',
            'gid'
        );

        $this->createIndex(
            'idx-project_custom_fields-project_gid',
            '{{%project_custom_fields}}',
            'project_gid'
        );

        // Создание таблицы для хранения enum_options кастомных полей
        $this->createTable('{{%project_custom_field_enum_options}}', [
            'id' => $this->primaryKey(),
            'custom_field_gid' => $this->string()->notNull(),
            'gid' => $this->string()->notNull()->unique(),
            'name' => $this->string()->notNull(),
            'color' => $this->string(),
            'enabled' => $this->boolean()->defaultValue(true),
            'resource_type' => $this->string(),
        ]);

        // Индекс для custom_field_gid
        $this->createIndex(
            'idx-project_custom_field_enum_options-custom_field_gid',
            '{{%project_custom_field_enum_options}}',
            'custom_field_gid'
        );

        // Внешний ключ для custom_field_gid, связывающий enum_options с project_custom_fields
        $this->addForeignKey(
            'fk-project_custom_field_enum_options-custom_field_gid',
            '{{%project_custom_field_enum_options}}',
            'custom_field_gid',
            '{{%project_custom_fields}}',
            'gid',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаляем внешние ключи и индексы перед удалением таблиц
        $this->dropForeignKey('fk-project_custom_field_enum_options-custom_field_gid', '{{%project_custom_field_enum_options}}');
        $this->dropIndex('idx-project_custom_field_enum_options-custom_field_gid', '{{%project_custom_field_enum_options}}');
        $this->dropIndex('idx-project_custom_fields-gid', '{{%project_custom_fields}}');
        $this->dropIndex('idx-project_custom_fields-project_gid', '{{%project_custom_fields}}');

        // Удаляем таблицы
        $this->dropTable('{{%project_custom_field_enum_options}}');
        $this->dropTable('{{%project_custom_fields}}');
    }
}

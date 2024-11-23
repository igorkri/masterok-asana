<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task}}`.
 */
class m241027_081832_create_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Создание основной таблицы для хранения данных задач Asana
        $this->createTable('{{%task}}', [
            'id' => $this->primaryKey(),                     // Уникальный идентификатор записи в базе данных (автоинкремент)
            'gid' => $this->string()->notNull()->unique(),   // Уникальный идентификатор задачи в Asana
            'name' => $this->text()->notNull(),              // Название задачи (тип text для поддержки длинных строк)

            // Поля для назначенного пользователя (принимают null, если задача не назначена)
            'assignee_gid' => $this->string()->null(),       // Идентификатор назначенного пользователя
            'assignee_name' => $this->string()->null(),      // Имя назначенного пользователя
            'assignee_status' => $this->string()->null(),    // Статус назначения

            // Статус задачи и информация о завершении
            'section_project_gid' => $this->string()->null(),    // Идентификатор проекта секции
            'section_project_name' => $this->string()->null(),    // Название проекта секции
            'completed' => $this->boolean()->defaultValue(false), // Флаг завершенности задачи
            'completed_at' => $this->dateTime()->null(),          // Дата и время завершения задачи

            // Временные метки для задачи
            'created_at' => $this->dateTime()->notNull(),    // Дата создания задачи
            'due_on' => $this->date()->null(),               // Дата, к которой задача должна быть завершена
            'start_on' => $this->date()->null(),             // Дата начала задачи

            'notes' => $this->text()->null(),                // Заметки или описание задачи
            'permalink_url' => $this->string()->null(),      // Прямая ссылка на задачу в интерфейсе Asana

            // Идентификаторы проекта и рабочего пространства для задачи
            'project_gid' => $this->string()->null(),        // Идентификатор проекта
            'workspace_gid' => $this->string()->notNull(),   // Идентификатор рабочего пространства

            'modified_at' => $this->dateTime()->null(),      // Дата последнего изменения задачи
            'resource_subtype' => $this->string()->null(),   // Подтип ресурса задачи

            // Счётчики "сердечек" и "лайков" для задачи
            'num_hearts' => $this->integer()->defaultValue(0), // Количество "сердечек"
            'num_likes' => $this->integer()->defaultValue(0),  // Количество "лайков"
        ]); // Кодировка utf8mb4 для поддержки эмодзи

        // Индекс для ускорения поиска задач по project_gid
        $this->createIndex(
            'idx-task-project_gid',
            '{{%task}}',
            'project_gid'
        );

        // Индекс для ускорения поиска задач по workspace_gid
        $this->createIndex(
            'idx-task-workspace_gid',
            '{{%task}}',
            'workspace_gid'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаление индексов перед удалением таблицы
        $this->dropIndex('idx-task-project_gid', '{{%task}}');
        $this->dropIndex('idx-task-workspace_gid', '{{%task}}');

        // Удаление таблицы task
        $this->dropTable('{{%task}}');
    }
}

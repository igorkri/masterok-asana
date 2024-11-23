<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%section_project}}`.
 */
class m241026_192026_create_section_project_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%section_project}}', [
            'id' => $this->primaryKey(),
            'gid' => $this->bigInteger()->notNull(),
            'name' => $this->string()->notNull(),
            'project_gid' => $this->bigInteger()->notNull(),
            'resource_type' => $this->string()->null(),
        ]);

        $this->createIndex(
            'idx-section_project-project_id',
            'section_project',
            'project_gid'
        );

        $this->addForeignKey(
            'fk-section_project-project_id',
            'section_project',
            'project_gid',
            'project',
            'gid',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаление внешнего ключа перед удалением таблицы
        $this->dropForeignKey(
            'fk-section_project-project_id',
            'section_project'
        );

        // Удаление индекса перед удалением таблицы
        $this->dropIndex(
            'idx-section_project-project_id',
            'section_project'
        );

        // Удаление таблицы
        $this->dropTable('{{%section_project}}');
    }
}

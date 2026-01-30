<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%translator}}`.
 */
class m260129_140826_create_translator_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%translator}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'email' => $this->string(255)->null(),
            'is_active' => $this->boolean()->notNull()->defaultValue(true),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex('idx_translator_is_active', '{{%translator}}', 'is_active');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%translator}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%translator_availability}}`.
 */
class m260129_141132_create_translator_availability_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%translator_availability}}', [
            'id' => $this->primaryKey(),
            'translator_id' => $this->integer()->notNull(),
            // 1=Sunday, 2=Monday ... 7=Saturday (MySQL DAYOFWEEK format)
            'day_of_week' => $this->tinyInteger()->notNull(),
            'is_available' => $this->boolean()->notNull()->defaultValue(true),
            'created_at' => $this->timestamp()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex('ux_translator_day', '{{%translator_availability}}', ['translator_id', 'day_of_week'], true);
        $this->createIndex('idx_availability_day', '{{%translator_availability}}', ['day_of_week', 'is_available']);

        $this->addForeignKey(
            'fk_availability_translator',
            '{{%translator_availability}}',
            'translator_id',
            '{{%translator}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_availability_translator', '{{%translator_availability}}');
        $this->dropTable('{{%translator_availability}}');
    }
}

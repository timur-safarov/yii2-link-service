<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%link}}`.
 */
class m240525_010444_create_link_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%link}}', [
            'id' => $this->primaryKey()->notNull()->unsigned()->comment('ID'),
            'link' => $this->string(1000)->notNull()->comment('Ссылка'),
            'short_link' => $this->char(10)->notNull()->unique()->comment('Короткая ссылка'),
            'hash_link' => $this->char(64)->notNull()->unique()->comment('Хэш ссылки'),
            'created_at' => $this->integer()->notNull()->unsigned()->comment('Дата создания'),
            'updated_at' => $this->integer()->notNull()->unsigned()->comment('Дата обновления'),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%link}}');
    }
}

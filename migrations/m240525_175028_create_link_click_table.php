<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%link_click}}`.
 */
class m240525_175028_create_link_click_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%link_click}}', [
            'id' => $this->primaryKey()->notNull()->unsigned()->comment('ID'),
            'id_link' => $this->integer()->notNull()->unsigned()->comment('Ссылка'),
            'ip' => $this->string(32)->notNull()->comment('IP'),
            'view' => $this->integer()->notNull()->unsigned()->comment('Переходы по ссылке'),
            'created_at' => $this->integer()->notNull()->unsigned()->comment('Дата создания'),
            'updated_at' => $this->integer()->notNull()->unsigned()->comment('Дата обновления'),
        ]);

        // Ставим состовной уникальный индекс чтобы обновлять поля в соответствии с ip или с FK ключом
        $this->createIndex(
            'idx-id_link-ip',
            '{{%link_click}}',
            ['id_link', 'ip'],
            true
        );

        // Добавим внешний ключ
        $this->addForeignKey(
            'fk-link_click-id_link-link-id',
            '{{%link_click}}', ['id_link'],
            '{{%link}}', ['id'],
            'CASCADE',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-link_click-id_link-link-id', '{{%link_click}}');
        $this->dropTable('{{%link_click}}');
    }
}

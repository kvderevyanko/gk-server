<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%device}}`.
 */
class m200525_212321_create_device_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%device}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'host' => $this->string()->notNull(),
            'active' => $this->boolean(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%device}}');
    }
}

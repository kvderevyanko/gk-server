<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dht}}`.
 */
class m200531_225955_create_dht_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dht}}', [
            'id' => $this->primaryKey(),
            'deviceId' => $this->integer()->notNull(),
            'name' => $this->string(),
            'pin' => $this->integer()->notNull(),
            'active' => $this->boolean(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%dht}}');
    }
}

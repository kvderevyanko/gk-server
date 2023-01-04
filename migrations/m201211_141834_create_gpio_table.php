<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%gpio}}`.
 */
class m201211_141834_create_gpio_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%gpio}}', [
            'id' => $this->primaryKey(),
            'deviceId' => $this->integer()->notNull(),
            'name' => $this->string(),
            'pin' => $this->integer()->notNull(),
            'value' => $this->boolean(),
            'active' => $this->boolean(),
            'home' => $this->boolean(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%gpio}}');
    }
}

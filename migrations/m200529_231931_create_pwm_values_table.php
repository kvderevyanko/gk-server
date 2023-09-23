<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pwm_values}}`.
 */
class m200529_231931_create_pwm_values_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pwm_values}}', [
            'id' => $this->primaryKey(),
            'deviceId' => $this->integer()->notNull(),
            'pin' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'value' => $this->integer(),
            'active' => $this->boolean(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pwm_values}}');
    }
}

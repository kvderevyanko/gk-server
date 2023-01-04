<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pwm_settings}}`.
 */
class m200529_224119_create_pwm_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pwm_settings}}', [
            'id' => $this->primaryKey(),
            'deviceId' => $this->integer()->notNull(),
            'clock' => $this->integer()->notNull(),
            'duty' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pwm_settings}}');
    }
}

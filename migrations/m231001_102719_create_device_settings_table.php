<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%device_settings}}`.
 */
class m231001_102719_create_device_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%device_settings}}', [
            'id' => $this->primaryKey(),
            'deviceId' => $this->integer(),
            'type' => $this->string(),
        ]);

        if($this->db->driverName !== "sqlite") {
            $this->addForeignKey(
                'device_settings_deviceId',
                '{{%device_settings}}',
                'deviceId',
                'device',
                'id',
                'CASCADE'
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%device_settings}}');

        if($this->db->driverName !== "sqlite") {
            $this->dropForeignKey(
                'device_settings_deviceId',
                '{{%device_settings}}',
            );
        }
    }
}

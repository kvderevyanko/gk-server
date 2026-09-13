<?php

use yii\db\Migration;

/**
 * Stores the result of a command delivery attempt without changing desired state.
 */
class m260913_000000_create_command_delivery_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%command_delivery}}', [
            'id' => $this->primaryKey(),
            'deviceId' => $this->integer()->notNull(),
            'type' => $this->string(24)->notNull(),
            'pin' => $this->integer(),
            'status' => $this->string(16)->notNull(),
            'message' => $this->text()->notNull(),
            'payload' => $this->text(),
            'createdAt' => $this->integer()->notNull(),
        ]);
        $this->createIndex(
            'idx-command_delivery-device_created',
            '{{%command_delivery}}',
            ['deviceId', 'createdAt']
        );
        $this->createIndex(
            'idx-command_delivery-status_created',
            '{{%command_delivery}}',
            ['status', 'createdAt']
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%command_delivery}}');
    }
}

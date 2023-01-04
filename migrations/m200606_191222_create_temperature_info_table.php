<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%temperature_info}}`.
 */
class m200606_191222_create_temperature_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%temperature_info}}', [
            'id' => $this->primaryKey(),
            'deviceId' => $this->integer()->notNull(),
            'temperature' => $this->float(),
            'humidity' => $this->float(),
            'datetime' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%temperature_info}}');
    }
}

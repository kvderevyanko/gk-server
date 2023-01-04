<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ws_values}}`.
 */
class m200531_162738_create_ws_values_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ws_values}}', [
            'id' => $this->primaryKey(),
            'deviceId' => $this->integer()->notNull(),
            'name' => $this->string(),
            'defaultBuffer' => $this->integer()->notNull(),
            'buffer' => $this->integer(),
            'mode' => $this->string(),
            'delay' => $this->integer(),
            'bright' => $this->integer(),
            'singleColor' => $this->string(),
            'gradientColor' => $this->text(),
            'modeOptions' => $this->string(),
            'active' => $this->boolean(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ws_values}}');
    }
}

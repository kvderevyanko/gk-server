<?php

use yii\db\Migration;

/**
 * Class m210113_130828_add_settings_value
 */
class m210113_130828_add_settings_value extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('settings', [
            'key' => \app\models\Settings::MOTOR_INTERVAL,
            'value' => 3,
            'active' => true
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('settings', [
            'key' => \app\models\Settings::MOTOR_INTERVAL
        ]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210113_130828_add_settings_value cannot be reverted.\n";

        return false;
    }
    */
}

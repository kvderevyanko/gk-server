<?php

use yii\db\Migration;

/**
 * Class m200625_192947_add_fields
 */
class m200625_192947_add_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('device', 'class', $this->string());
        $this->addColumn('device', 'home', $this->boolean());
        $this->addColumn('dht', 'home', $this->boolean());
        $this->addColumn('ws_values', 'home', $this->boolean());
        $this->addColumn('pwm_values', 'home', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('device', 'class');
        $this->dropColumn('device', 'home');
        $this->dropColumn('dht', 'home');
        $this->dropColumn('ws_values', 'home');
        $this->dropColumn('pwm_values', 'home');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200625_192947_add_fields cannot be reverted.\n";

        return false;
    }
    */
}

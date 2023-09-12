<?php

use yii\db\Migration;

/**
 * Class m230912_113633_add_valuue_settings
 */
class m230912_113633_add_valuue_settings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('settings', [
            'key' => \app\models\Settings::SITE_NAME,
            'value' => '',
            'active' => true
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('settings', [
            'key' => \app\models\Settings::SITE_NAME
        ]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230912_113633_add_valuue_settings cannot be reverted.\n";

        return false;
    }
    */
}

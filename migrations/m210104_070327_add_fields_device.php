<?php

use yii\db\Migration;

/**
 * Class m210104_070327_add_fields_device
 */
class m210104_070327_add_fields_device extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('device', 'type', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('device', 'type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210104_070327_add_fields_device cannot be reverted.\n";

        return false;
    }
    */
}

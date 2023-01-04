<?php

use yii\db\Migration;

/**
 * Class m210104_063032_add_fields_gpio_table
 */
class m210104_063032_add_fields_gpio_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('gpio', 'motor', $this->boolean());
        $this->addColumn('gpio', 'negative', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('gpio', 'motor');
        $this->dropColumn('gpio', 'negative');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210104_063032_add_fields_gpio_table cannot be reverted.\n";

        return false;
    }
    */
}

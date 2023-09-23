<?php

use yii\db\Migration;

/**
 * Class m230923_064503_add_field_temperature_info_table
 */
class m230923_064503_add_field_temperature_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%temperature_info}}',
            'pin',
            $this->integer()->notNull()->after('deviceId')->defaultValue(0)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(
            '{{%temperature_info}}',
            'pin',
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230923_064503_add_field_temperature_info_table cannot be reverted.\n";

        return false;
    }
    */
}

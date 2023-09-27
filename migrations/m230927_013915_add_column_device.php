<?php

use yii\db\Migration;

/**
 * Class m230927_013915_add_column_device
 */
class m230927_013915_add_column_device extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%device}}',
            'icon',
            $this->string()->after('active')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%device}}', 'icon');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230927_013915_add_column_device cannot be reverted.\n";

        return false;
    }
    */
}

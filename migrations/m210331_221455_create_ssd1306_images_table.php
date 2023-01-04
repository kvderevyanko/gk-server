<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ssd1306_images}}`.
 */
class m210331_221455_create_ssd1306_images_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ssd1306_images}}', [
            'id' => $this->primaryKey(),
            'width' => $this->integer(),
            'height' => $this->integer(),
            'size' => $this->integer(),
            'code' => $this->text(),
            'html' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ssd1306_images}}');
    }
}

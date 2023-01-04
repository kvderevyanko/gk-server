<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%commands}}`.
 */
class m210104_213522_create_commands_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%commands}}', [
            'id' => $this->primaryKey(),
            'deviceId' => $this->integer()->notNull(),
            'pinType' => $this->string()->notNull(),
            'pin' => $this->integer()->notNull(),
            'conditionType' => $this->string()->notNull(),
            'conditionFrom' => $this->integer()->notNull(),
            'conditionTo' => $this->integer()->notNull(),
            'pinValue' => $this->integer()->notNull(),
            'conditionSort' => $this->integer()->notNull(),
            'active' => $this->boolean(),
        ]);

        /*
            'id' => $this->primaryKey(),
            'deviceType' => $this->string()->notNull()->comment('Тип (gpio, pwm)'),
            'pin' => $this->integer()->notNull()->comment('Пин'),
            'conditionType' => $this->string()->notNull()->comment('Тип условия (время, температура)'),
            'conditionFrom' => $this->integer()->notNull()->comment('Значение условия (время, температура) от'),
            'conditionTo' => $this->integer()->notNull()->comment('Значение условия до'),
            'pinValue' => $this->integer()->notNull()->comment('Значение пина'),
            'conditionSort' => $this->integer()->notNull()->comment('Сортировка условия'),
            'active' => $this->boolean()->comment('Активность условия'),
         */
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%commands}}');
    }
}

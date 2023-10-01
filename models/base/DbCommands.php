<?php

namespace app\models\base;

/**
 * This is the model class for table "commands".
 *
 * @property int $id
 * @property int $deviceId
 * @property string $pinType
 * @property int $pin
 * @property string $conditionType
 * @property int $conditionFrom
 * @property int $conditionTo
 * @property int $pinValue
 * @property int $conditionSort
 * @property bool|null $active
 */
class DbCommands extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'commands';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['deviceId', 'pinType', 'pin', 'conditionType', 'conditionFrom', 'conditionTo', 'pinValue', 'conditionSort'], 'required'],
            [['pin', 'conditionFrom', 'conditionTo', 'pinValue', 'conditionSort'], 'integer'],
            [['active'], 'boolean'],
            [['pinType', 'conditionType'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'deviceId' => 'Id устройства',
            'pinType' => 'Тип (gpio, pwm)',
            'pin' => 'Пин',
            'conditionType' => 'Тип условия (время, температура)',
            'conditionFrom' => 'Значение условия (время, температура) от',
            'conditionTo' => 'Значение условия до',
            'pinValue' => 'Значение пина',
            'conditionSort' => 'Сортировка условия',
            'active' => 'Активно', //Активность условия
        ];
    }

}

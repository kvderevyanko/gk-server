<?php

namespace app\models;

use Yii;

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
class Commands extends \yii\db\ActiveRecord
{

    const PIN_TYPE_PWM = 'pwm';
    const PIN_TYPE_GPIO = 'gpio';
    const CONDITION_TYPE_TIME = 'time';
    const CONDITION_TYPE_TEMPERATURE = 'temperature';

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

    public static function deviceTypeList(): array
    {
        return [
            self::PIN_TYPE_GPIO => 'GPIO',
            self::PIN_TYPE_PWM => 'PWM',
        ];
    }

    public static function conditionsList(): array
    {
        return [
            self::CONDITION_TYPE_TIME => 'Время',
            self::CONDITION_TYPE_TEMPERATURE => 'Температура',
        ];
    }
}

<?php

namespace app\models;

use app\models\base\DbCommands;

class Commands extends DbCommands
{

    const PIN_TYPE_PWM = 'pwm';
    const PIN_TYPE_GPIO = 'gpio';
    const CONDITION_TYPE_TIME = 'time';
    const CONDITION_TYPE_TEMPERATURE = 'temperature';

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

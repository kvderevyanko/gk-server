<?php
namespace app\components\CommandsSettings\get;
use app\models\Commands;

/**
 * Работа с командами для GPIO
 */
class CommandsGpioGetSettings implements CommandsGetSettingsInterface {
    static function get(int $deviceId, int $pin): array
    {
        return Commands::find()
            ->where(['deviceId' => $deviceId, 'pin' => $pin, 'pinType' => Commands::PIN_TYPE_GPIO])
            ->orderBy(['conditionSort' => SORT_ASC])->all();
    }
}
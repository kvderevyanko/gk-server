<?php
namespace app\components\CommandsSettings\set;
use app\models\Commands;

/**
 * Работа с командами для GPIO
 */
class CommandsGpioSetSettings implements CommandsSetSettingsInterface {


    static function set(int $deviceId, int $pin, array $commands): bool
    {
        Commands::deleteAll(['deviceId' => $deviceId, 'pin' => $pin, 'pinType' => Commands::PIN_TYPE_GPIO]);
        if(array_key_exists('conditionType', $commands) && is_array($commands['conditionType'])) {
            $i = 0;
            foreach ($commands['conditionType'] as $key => $conditionType) {
                $command = new Commands();
                $command->deviceId = $deviceId;
                $command->pin = $pin;
                $command->pinType = Commands::PIN_TYPE_GPIO;
                $command->conditionType = $conditionType;

                if(array_key_exists('conditionFrom', $commands) && is_array($commands['conditionFrom']) &&
                    array_key_exists($key, $commands['conditionFrom'])) {
                    $command->conditionFrom = $commands['conditionFrom'][$key];
                }

                if(array_key_exists('conditionTo', $commands) && is_array($commands['conditionTo']) &&
                    array_key_exists($key, $commands['conditionTo'])) {
                    $command->conditionTo = $commands['conditionTo'][$key];
                }

                if(array_key_exists('pinValue', $commands) && is_array($commands['pinValue']) &&
                    array_key_exists($key, $commands['pinValue'])) {
                    $command->pinValue = $commands['pinValue'][$key];
                }

                if(array_key_exists('active', $commands) && is_array($commands['active']) &&
                    array_key_exists($key, $commands['active'])) {
                    $command->active = $commands['active'][$key];
                }
                $command->conditionSort = $i;
                $command->save();
                $i++;
            }
        }
        return true;
    }
}
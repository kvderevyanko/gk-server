<?php
namespace app\components\CommandsSettings\set;
/**
 * Работа с командами пинов
 */
interface CommandsSetSettingsInterface {
    /**
     * Установка команды
     * @param int $deviceId
     * @param int $pin
     * @param array $commands
     * @return bool
     */
    static function set(int $deviceId, int $pin, array $commands):bool;


}
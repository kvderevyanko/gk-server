<?php
namespace app\components\CommandsSettings;
/**
 * Работа с командами пинов
 */
interface CommandsSettingsInterface {
    /**
     * Установка команды
     * @param int $deviceId
     * @param int $pin
     * @param array $commands
     * @return bool
     */
    static function set(int $deviceId, int $pin, array $commands):bool;

    /**
     * Получение команд для пина
     * @param int $deviceId
     * @param int $pin
     * @return array
     */
    static function get(int $deviceId, int $pin):array;

}
<?php
namespace app\components\CommandsSettings\get;
/**
 * Работа с командами пинов
 */
interface CommandsGetSettingsInterface {
    /**
     * Получение команд для пина
     * @param int $deviceId
     * @param int $pin
     * @return array
     */
    static function get(int $deviceId, int $pin):array;

}
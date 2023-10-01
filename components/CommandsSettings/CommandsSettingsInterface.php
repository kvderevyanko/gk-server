<?php
namespace app\components\CommandsSettings;
/**
 * Работа с командами пинов
 */
interface CommandsSettingsInterface {
    /**
     * Установка команды
     * @return string
     */
    function set():string;

    /**
     * Обновление команды
     * @return string
     */
    function update():string;

    /**
     * Удаление команды
     * @return string
     */
    function delete():string;

    /**
     * Получение результата выполнения команды
     * @return string
     */
    function getResult():string;


    /**
     * Выполнение команды
     * @return string
     */
    function runCommand():string;
}
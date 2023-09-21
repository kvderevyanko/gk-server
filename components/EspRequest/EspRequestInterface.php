<?php
namespace app\components\EspRequest;
/**
 * Отправка запроса на микроконтроллер
 */
interface EspRequestInterface{
    /**
     * Отправка запроса на esp
     * @return string
     */
    function send():string;
}
<?php
namespace app\components\EspRequest;
use yii\base\ViewContextInterface;

/**
 * Отправка запроса на микроконтроллер
 */
interface EspRequestInterface {
    /**
     * Отправка запроса на esp
     * @return string
     */
    function send():string;
}
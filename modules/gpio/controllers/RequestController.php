<?php

namespace app\modules\gpio\controllers;


use app\modules\gpio\models\Gpio;
use yii\httpclient\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Класс для получения запроса из фронта с изменением значения пина
 */
class RequestController extends Controller
{

    /**
     * Получение запроса со значением пинов для  устройства
     * @param int $deviceId
     * @param int $pin
     * @param bool $value
     * @return string
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionSet(int $deviceId, int $pin, bool $value): string
    {
        Gpio::setStatus($deviceId, $pin, $value);
        return Gpio::sendRequest($deviceId);
    }
}

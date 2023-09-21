<?php

namespace app\modules\gpio\models;

use app\components\EspRequest\EspRequest;
use app\models\Device;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;
use yii\web\NotFoundHttpException;

class Gpio extends DbGpio
{

    /**
     * Отправка запроса по GPIO
     * @param $deviceId
     * @return string
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     * @throws Exception
     */
    public static function sendRequest($deviceId): string
    {
        $gpios = self::findAll(['deviceId' => $deviceId, 'active' => self::STATUS_ACTIVE]);

        $params = [];
        foreach ($gpios as $gpio) {
            if($gpio->negative) {
                $params[$gpio->pin] = $gpio->value?0:1;
            } else {
                $params[$gpio->pin] = $gpio->value;
            }
        }

        $device = Device::getActiveDevice($deviceId);

        return (new EspRequest($device->host,'gpio.lc', $params))->send();
    }

}

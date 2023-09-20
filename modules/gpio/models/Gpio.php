<?php

namespace app\modules\gpio\models;

use app\components\EspRequest;
use app\models\DbGpio;
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
    public static function sendRequest($deviceId) {
        $gpios = self::findAll(['deviceId' => $deviceId, 'active' => self::STATUS_ACTIVE]);

        $request = [];
        foreach ($gpios as $gpio) {
            if($gpio->negative) {
                $request[$gpio->pin] = $gpio->value?0:1;
            } else {
                $request[$gpio->pin] = $gpio->value;
            }
        }
        $device = Device::getActiveDevice($deviceId);
        return EspRequest::send(trim($device->host).'gpio.lc', $request);
    }

}

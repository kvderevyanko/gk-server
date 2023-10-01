<?php

namespace app\modules\gpio\models;

use app\components\EspRequest\EspRequest;
use app\components\EspRequest\EspRequestSenderFactory;
use app\models\Device;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;
use yii\web\NotFoundHttpException;

class Gpio extends DbGpio
{
    const STATUS_ACTIVE = 1;
    const STATUS_NO_ACTIVE = 0;
    /**
     * Отправка запроса по GPIO
     * @param int $deviceId
     * @return string
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public static function sendRequest(int $deviceId): string
    {
        $gpioList = self::find()
            ->leftJoin(Device::tableName(), Device::tableName().".id = ".self::tableName().".deviceId")
            ->andWhere([
                Device::tableName().".active" => Device::STATUS_ACTIVE,
                self::tableName().'.deviceId' => $deviceId,
                self::tableName().'.active' => self::STATUS_ACTIVE
            ])
            ->all();

        $params = [];
        foreach ($gpioList as $gpio) {
            if($gpio->negative) {
                $params[$gpio->pin] = $gpio->value?0:1;
            } else {
                $params[$gpio->pin] = $gpio->value;
            }
        }

        $device = Device::getActiveDevice($deviceId);

        $requestSenderFactory = new EspRequestSenderFactory();
        $espRequest = $requestSenderFactory->createEspRequest($device->host,'gpio.lc', $params);
        return $espRequest->send();
    }

    /**
     * Запись значения GPIO в базу
     * @param int $deviceId
     * @param int $pin
     * @param bool $value
     * @return bool
     */
    public static function setStatus(int $deviceId, int $pin, bool $value): bool
    {
        $gpio = self::find()
            ->leftJoin(Device::tableName(), Device::tableName().".id = ".self::tableName().".deviceId")
            ->andWhere([
                self::tableName().'.deviceId' => $deviceId,
                self::tableName().'.active' => self::STATUS_ACTIVE,
                self::tableName().'.pin' => $pin,
                Device::tableName().".active" => Device::STATUS_ACTIVE,

            ])
            ->one();
        if($gpio) {
            $gpio->value = $value;
            return $gpio->save();
        }
        return false;
    }

}

<?php

namespace app\modules\gpio\models;

use app\models\DbGpio;
use app\models\Device;
use yii\httpclient\Client;
use yii\web\NotFoundHttpException;

class Gpio extends DbGpio
{


    /**
     * @param integer $deviceId
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
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

        $device = Device::findOne($deviceId);
        if($device === null)
            throw new NotFoundHttpException("Устройство не найдено");

        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl(trim($device->host).'gpio.lc')
            ->setData($request)
            ->setOptions([
                'timeout' => 2,
            ])
            ->send();
        if ($response->isOk) {
            return $response->content;
        }
        return 'error';
    }

}

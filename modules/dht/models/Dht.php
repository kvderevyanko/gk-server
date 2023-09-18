<?php

namespace modules\dht\models;

use app\models\Device;
use PHPUnit\Exception;
use yii\helpers\Json;
use yii\httpclient\Client;

class Dht extends \app\models\Dht
{


    const STATUS_ACTIVE = 1;
    const STATUS_NO_ACTIVE = 0;





    public function lastTemperature(){
        $temperatureInfo = TemperatureInfo::find()->where(['deviceId' => $this->deviceId])->orderBy(['id' => SORT_DESC])->one();
        return $temperatureInfo;
    }

    public static function sendRequest($deviceId) {
        $dht = self::findOne(['deviceId' => $deviceId, 'active' => self::STATUS_ACTIVE]);
        if($dht) {
            $client = new Client([
                'transport' => 'yii\httpclient\CurlTransport'
            ]);
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl($dht->device->host.'dht')
                ->setData([
                    'pin' => $dht->pin,
                ])
                ->setOptions([
                    'timeout' => 2, // set timeout to 5 seconds for the case server is not responding
                ])
                ->send();
            if ($response->isOk) {
                try {
                    $content = $response->content;
                    $content = Json::decode($content);
                    if($content['status'] === 'ok') {
                        $temperatureInfo = new TemperatureInfo();
                        $temperatureInfo->temperature = array_key_exists('temperature', $content)?$content['temperature']:'';
                        $temperatureInfo->humidity = array_key_exists('humidity', $content)?$content['humidity']:'';
                        $temperatureInfo->deviceId = $deviceId;
                        $temperatureInfo->datetime = time();
                        $temperatureInfo->save();
                    }
                } catch (Exception $e) {

                }
                return $response->content;
            }
        }

    }
}

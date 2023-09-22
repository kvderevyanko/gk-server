<?php

namespace app\modules\dht\models;

use app\components\EspRequest\EspRequest;
use app\models\Device;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

class Dht extends DbDht
{

    /**
     * @return TemperatureInfo|array|\yii\db\ActiveRecord|null
     */
    public function lastTemperature(){
        $temperatureInfo = TemperatureInfo::find()
            ->where(['deviceId' => $this->deviceId])
            ->orderBy(['id' => SORT_DESC])->one();
        return $temperatureInfo;
    }

    /**
     * @param int $deviceId
     * @return string
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @throws NotFoundHttpException
     */
    public static function sendRequest(int $deviceId): string
    {
        $dht = self::findOne(['deviceId' => $deviceId, 'active' => self::STATUS_ACTIVE]);
        if($dht) {
            $device = Device::getActiveDevice($deviceId);

            $params = ['pin' => $dht->pin];

            $content = (new EspRequest($device->host,'dht.lc', $params))->send();

            if ($content != EspRequest::RESPONSE_ERROR) {
                try {
                    $content = Json::decode($content);
                    if($content['status'] === 'ok') {
                        $temperatureInfo = new TemperatureInfo();
                        $temperatureInfo->temperature = array_key_exists('temperature', $content)?$content['temperature']:'';
                        $temperatureInfo->humidity = array_key_exists('humidity', $content)?$content['humidity']:'';
                        $temperatureInfo->deviceId = $deviceId;
                        $temperatureInfo->datetime = time();
                        $temperatureInfo->save();
                    }
                } catch (\Exception $e) {

                }
                return Json::encode($content);
            }
        }
        return EspRequest::RESPONSE_ERROR;
    }
}

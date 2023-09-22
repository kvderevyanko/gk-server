<?php

namespace app\modules\dht\models;

use app\components\EspRequest\EspRequest;
use app\components\EspRequest\EspRequestSenderFactory;
use app\models\Device;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\httpclient\Exception;
use yii\web\NotFoundHttpException;

class Dht extends DbDht
{

    /**
     * @return TemperatureInfo|array|ActiveRecord|null
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
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public static function sendRequest(int $deviceId): string
    {
        $dht = self::findOne(['deviceId' => $deviceId, 'active' => self::STATUS_ACTIVE]);
        if($dht) {
            $device = Device::getActiveDevice($deviceId);

            $params = ['pin' => $dht->pin];

            $requestSenderFactory = new EspRequestSenderFactory();
            $espRequest = $requestSenderFactory->createEspRequest($device->host,'dht.lc', $params);
            $content = $espRequest->send();

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

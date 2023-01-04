<?php

namespace app\models;

use PHPUnit\Exception;
use Yii;
use yii\helpers\Json;
use yii\httpclient\Client;

/**
 * This is the model class for table "dht".
 *
 * @property int $id
 * @property int $deviceId
 * @property string|null $name
 * @property int $pin
 * @property bool|null $active
 * @property bool|null $home
 */
class Dht extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dht';
    }

    const STATUS_ACTIVE = 1;
    const STATUS_NO_ACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deviceId', 'pin'], 'required'],
            [['deviceId', 'pin'], 'integer'],
            [['pin'], 'integer', 'min' => 1, 'max' => 12],
            [['active', 'home'], 'boolean'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'deviceId' => 'Основное устройство',
            'name' => 'Имя',
            'pin' => 'Пин',
            'active' => 'Активно',
            'home' => 'На главной',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevice(){
        return $this->hasOne(Device::className(), ['id' => 'deviceId']);
    }

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

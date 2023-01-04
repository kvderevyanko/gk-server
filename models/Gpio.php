<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "gpio".
 *
 * @property int $id
 * @property int $deviceId
 * @property string|null $name
 * @property int $pin
 * @property bool|null $value
 * @property bool|null $active
 * @property bool|null $home
 * @property bool|null $motor
 * @property bool|null $negative
 */
class Gpio extends \yii\db\ActiveRecord
{

    const STATUS_ACTIVE = 1;
    const STATUS_NO_ACTIVE = 0;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gpio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deviceId', 'pin'], 'required'],
            [['deviceId', 'pin'], 'integer'],
            [['value', 'active', 'home', 'motor', 'negative'], 'boolean'],
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
            'value' => 'Значение',
            'active' => 'Активно',
            'home' => 'На главной',
            'motor' => 'Мотор',
            'negative' => 'Негатив',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevice(){
        return $this->hasOne(Device::className(), ['id' => 'deviceId']);
    }

    public static function sendRequest($deviceId) {
        $pwms = self::findAll(['deviceId' => $deviceId, 'active' => self::STATUS_ACTIVE]);

        $request = [];
        foreach ($pwms as $pwm) {
            if($pwm->negative) {
                $request[$pwm->pin] = $pwm->value?0:1;
            } else {
                $request[$pwm->pin] = $pwm->value;
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
                'timeout' => 2, // set timeout to 5 seconds for the case server is not responding
            ])
            ->send();
        if ($response->isOk) {
            return $response->content;
        }
        return 'error';
    }

}

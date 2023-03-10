<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "pwm_values".
 *
 * @property int $id
 * @property int $deviceId
 * @property int $pinId
 * @property string $name
 * @property int|null $value
 * @property bool|null $active
 * @property bool|null $home
 *
 * @property Device getDevice()
 */
class PwmValues extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_NO_ACTIVE = 0;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pwm_values';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deviceId', 'pinId'], 'required'],
            [['deviceId', 'pinId', 'value'], 'integer'],
            [['active', 'home'], 'boolean'],
            [['name'], 'string'],
            [['deviceId', 'pinId'], 'unique', 'targetAttribute' => ['deviceId', 'pinId']],
            [['pinId'], 'integer', 'min' => 1, 'max' => 35],
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
            'pinId' => 'Пин',
            'name' => 'Имя',
            'value' => 'Значение',
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


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSettings(){
        return $this->hasOne(PwmSettings::className(), ['deviceId' => 'deviceId']);
    }

    public static function sendRequest($deviceId) {
        $pwm = self::findAll(['deviceId' => $deviceId, 'active' => self::STATUS_ACTIVE]);
        $request = ArrayHelper::map($pwm, 'pinId', 'value');
        $device = Device::findOne($deviceId);
        if($device === null)
            throw new NotFoundHttpException("Устройство не найдено");

        $pwmSettings = PwmSettings::findOne(['deviceId' => $deviceId]);
        if($pwmSettings === null)
            throw new NotFoundHttpException("Настройки PWM не найдены");



        $request['clock'] = $pwmSettings->clock;
        $request['duty'] = $pwmSettings->duty;
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($device->host.'gpio-pwm.lc')
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

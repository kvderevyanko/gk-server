<?php

namespace app\models;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "device".
 *
 * @property int $id
 * @property string|null $name
 * @property string $host
 * @property string $class
 * @property bool|null $active
 * @property bool|null $home
 * @property string $type
 */
class Device extends \yii\db\ActiveRecord
{

    const STATUS_ACTIVE = 1;
    const STATUS_NO_ACTIVE = 0;

    const TYPE_ESP_8266 = "ESP8266";
    const TYPE_ESP_32 = "ESP32";

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'device';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['host'], 'required'],
            [['active', 'home'], 'boolean'],
            [['name', 'host', 'class', 'type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'host' => 'Хост',
            'type' => 'Тип',
            'active' => 'Активно',
            'class' => 'Класс кнопки',
            'home' => 'На главной',
        ];
    }

    public static function btnClass()
    {
        return [
            'btn-default' => 'btn-default',
            'btn-primary' => 'btn-primary',
            'btn-success' => 'btn-success',
            'btn-info' => 'btn-info',
            'btn-warning' => 'btn-warning',
            'btn-danger' => 'btn-danger',
            'btn-link' => 'btn-link',
        ];
    }

    public static function typeList()
    {
        return [
            self::TYPE_ESP_8266 => self::TYPE_ESP_8266,
            //self::TYPE_ESP_32 => self::TYPE_ESP_32,
        ];
    }

    /**
     * Получение активного устройства
     * @param $deviceId
     * @return Device
     * @throws NotFoundHttpException
     */
    public static function getActiveDevice($deviceId){
        $device = Device::findOne(['id' => $deviceId, 'active' => self::STATUS_ACTIVE]);
        if($device === null)
            throw new NotFoundHttpException("Устройство не найдено");
        return $device;
    }

    public static function devicesList()
    {
        $devices = self::findAll(['active' => self::STATUS_ACTIVE]);
        return ArrayHelper::map($devices, 'id', 'name');
    }

    public static function deviceName($id)
    {
        $device = self::findOne($id);
        if ($device)
            return $device->name;

        return 'АХТУНГ!!! Основное устройство не найдено';
    }

    public function afterDelete()
    {
        parent::afterDelete();

        $pwmSettings = DbPwmSettings::findAll(['deviceId' => $this->id]);
        foreach ($pwmSettings as $settings)
            $settings->delete();

        $pwmValues = DbPwmValues::findAll(['deviceId' => $this->id]);
        foreach ($pwmValues as $values)
            $values->delete();

        $wsValues = DbWsValues::findAll(['deviceId' => $this->id]);
        foreach ($wsValues as $ws)
            $ws->delete();

        $gpioValues = DbGpio::findAll(['deviceId' => $this->id]);
        foreach ($gpioValues as $gpio)
            $gpio->delete();

        $dhtDevices = DbDht::findAll(['deviceId' => $this->id]);
        foreach ($dhtDevices as $dht)
            $dht->delete();

        //TemperatureInfo::deleteAll(['deviceId' => $this->id]);
    }
}

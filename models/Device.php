<?php
namespace app\models;

use app\models\base\DbDevice;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;


class Device extends DbDevice
{

    const STATUS_ACTIVE = 1;
    const STATUS_NO_ACTIVE = 0;
    const TYPE_ESP_8266 = "ESP8266";
    const TYPE_ESP_32 = "ESP32";


    /**
     * @return array
     */
    public static function typeList(): array
    {
        return [
            self::TYPE_ESP_8266 => self::TYPE_ESP_8266,
            //self::TYPE_ESP_32 => self::TYPE_ESP_32,
        ];
    }

    /**
     * Получение активного устройства
     * @param int $deviceId
     * @return Device
     * @throws NotFoundHttpException
     */
    public static function getActiveDevice(int $deviceId): Device
    {
        $device = Device::findOne(['id' => $deviceId, 'active' => self::STATUS_ACTIVE]);
        if($device === null)
            throw new NotFoundHttpException("Устройство не найдено");
        return $device;
    }

    /**
     * @return Device[]
     */
    public static function getActiveDevices(): array
    {
        return self::findAll(['active' => self::STATUS_ACTIVE]);
    }


    /**
     * @return array
     */
    public static function devicesList(): array
    {
        $devices = self::getActiveDevices();
        return ArrayHelper::map($devices, 'id', 'name');
    }


    /**
     * Генерация пунктов для меню
     * @return array
     */
    public static function menuList(): array
    {
        $devices = self::getActiveDevices();
        $menuList = [];
        foreach ($devices as $device) {
            $menuList[] = [
                'title'=>$device->name,
                'url'=>['/device/control/', 'device' => $device->id],
                'icon'=>$device->icon,

            ];
        }

        return $menuList;
    }

    /**
     * @param int $id
     * @return string
     */
    public static function deviceName(int $id): string
    {
        $device = self::findOne($id);
        if ($device)
            return $device->name;

        return 'АХТУНГ!!! Основное устройство не найдено';
    }

}

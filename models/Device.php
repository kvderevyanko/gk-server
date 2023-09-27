<?php

namespace app\models;
use app\modules\dht\models\DbDht;
use app\modules\dht\models\DbTemperatureInfo;
use app\modules\gpio\models\DbGpio;
use app\modules\pwm\models\DbPwmSettings;
use app\modules\pwm\models\DbPwmValues;
use app\modules\ws\models\DbWsValues;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;


class Device extends DbDevice
{

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

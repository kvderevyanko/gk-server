<?php

namespace app\models;

use app\models\base\DbDeviceSettings;
use Yii;


class DeviceSettings extends DbDeviceSettings
{
    const GPIO = "gpio";
    const PWM = "pwm";
    const WS2812 = "ws2812";
    const DTH = "dth";

    private static $_devices;


    /**
     * Список настроек
     * @return array
     */
    public static function settingsList(): array
    {
        return [
            self::GPIO => 'GPIO',
            self::PWM => 'PWM',
            self::WS2812 => 'WS2812',
            self::DTH => 'DTH',
        ];
    }

    /**
     * @return array
     */
    public static function settingsUrl(): array
    {
        return [
            self::GPIO => '/gpio/gpio/index',
            self::PWM => '/pwm/pwm-values/index',
            self::WS2812 => '/ws/ws-values/index',
            self::DTH => '/dht/dht/index',
        ];
    }

    /**
     * Возвращение урла для настройки
     * @param string $type
     * @return string
     */
    public static function getSettingsUrl(string $type): string
    {
        $list = self::settingsUrl();
        if(array_key_exists($type, $list)) {
            return $list[$type];
        }
        return '#';
    }

    /**
     * Сохранение отображаемых настроек для устройства
     * @param int $deviceId
     * @param array $settings
     * @return bool
     */
    public static function saveDeviceSettings(int $deviceId, array $settings): bool
    {
        self::deleteAll(['deviceId' => $deviceId]);
        foreach ($settings as $type => $val) {
            $setting = new self();
            $setting->deviceId = $deviceId;
            $setting->type = $type;
            $setting->save();
        }
        return true;
    }

    /**
     * Проверяет - активна ли настройка для устройства
     * @param int $deviceId
     * @param string $setting
     * @return bool
     */
    public static function checkDeviceSetting(int $deviceId, string $setting): bool
    {
        if(!self::$_devices || !is_array(self::$_devices) || !array_key_exists($deviceId, self::$_devices)) {
            if(!is_array(self::$_devices))
                self::$_devices = [];

            self::$_devices[$deviceId] = [];
            $settings = self::find()->select(['type'])->where(['deviceId' => $deviceId])->all();
            foreach ($settings as $set) {
                self::$_devices[$deviceId][$set->type] = 1;
            }
        }

        if(array_key_exists($setting, self::$_devices[$deviceId])) {
            return true;
        }
        return false;
    }

}

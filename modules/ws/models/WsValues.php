<?php

namespace app\modules\ws\models;

use app\components\EspRequest;
use app\models\DbWsValues;
use app\models\Device;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\httpclient\Exception;
use yii\web\NotFoundHttpException;


class WsValues extends DbWsValues
{

    public static $modeList = [
        'off' => 'Выключить',
        'static' => 'Статичный',
        'static-soft-blink' => 'Статичный мягкий мигающий',
        'static-soft-random-blink' => 'Случайный мягкий мигающий',
        'round-static' => 'По кругу статичный',
        'round-random' => 'По кругу случайный',
        'rainbow' => 'Радуга',
        'rainbow-circle' => 'Радуга по кругу',

/*        'blink-old' => '-- Мигающий',
        'gradient-old' => '-- Градиент',
        'random_color-old' => '-- Случайный цвет',
        'rainbow-old' => '-- Радуга',
        'rainbow_cycle-old' => '-- Радуга по кругу',
        'flicker-old' => '-- Мерцание',
        'fire-old' => '-- Огонь',
        'fire_soft-old' => '-- Огонь мягкий',
        'fire_intense-old' => '-- Огонь интенсивный',
        'halloween-old' => '-- Хэллоуин',
        'circus_combustus-old' => '-- Цирк',
        'larson_scanner-old' => '-- Сканер',
        'color_wipe-old' => '-- Заполнение цветом',
        'random_dot-old' => '-- Случайная точка',*/
    ];


    /**
     * @param $deviceId
     * @return string
     * @throws InvalidConfigException
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public static function sendRequest($deviceId) {

        $ws = self::findOne(['deviceId' => $deviceId, 'active' => self::STATUS_ACTIVE]);
        $device = Device::getActiveDevice($deviceId);
        if($ws) {
            $request = [
                'buffer' => $ws->buffer,
                'mode' => $ws->mode,
                'delay' => $ws->delay, //clock 1~1000, pwm frequency
                'bright' => $ws->bright, //duty 0~1023, pwm duty cycle, max 1023 (10bit)
                'single_color' => Json::encode(self::convertColor($ws->singleColor)), //grb
                'gradient_color' => Json::encode(self::convertHexString($ws->gradientColor)),
                'mode_options' => $ws->modeOptions,
            ];
            return EspRequest::send($device->host.'ws.lc', $request);
        }
        return 'error';
    }

    /**
     * Конвертируем строку из hex цветов и возвращаем массив с GRB цветками
     * @param $hexString
     * @return array
     */
    public static function convertHexString($hexString){
        $hexArray = explode(',', $hexString);
        $result = [];
        foreach ($hexArray as $hex) {
            $result = ArrayHelper::merge($result, self::convertColor($hex));
        }
        return array_values($result);
    }

    /**
     * Конвертирует hex в GRB для ws2812 nodemcu
     * @param $hex
     * @return array
     */
    public static function convertColor($hex) {
        $hex = trim($hex);
        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
        //echo "$hex -> $r $g $b";
        return [$r, $g, $b];
    }
}

<?php

namespace app\modules\ws\models;

use app\components\EspRequest\EspRequest;
use app\components\EspRequest\EspRequestSenderFactory;
use app\models\Device;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\httpclient\Exception;
use yii\web\NotFoundHttpException;


class WsValues extends DbWsValues
{
    /**
     * @var array|string[]
     */
    public static array $modeList = [
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
     * @param int $deviceId
     * @return string
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public static function sendRequest(int $deviceId): string
    {

        $ws = self::findOne(['deviceId' => $deviceId, 'active' => self::STATUS_ACTIVE]);
        $device = Device::getActiveDevice($deviceId);
        if($ws) {
            $params = [
                'buffer' => $ws->buffer,
                'mode' => $ws->mode,
                'delay' => $ws->delay, //clock 1~1000, pwm frequency
                'bright' => $ws->bright, //duty 0~1023, pwm duty cycle, max 1023 (10bit)
                'single_color' => Json::encode(self::convertColor($ws->singleColor)), //grb
                'gradient_color' => Json::encode(self::convertHexString($ws->gradientColor)),
                'mode_options' => $ws->modeOptions,
            ];
            $requestSenderFactory = new EspRequestSenderFactory();
            $espRequest = $requestSenderFactory->createEspRequest($device->host,'ws.lc', $params);
            return $espRequest->send();
        }
        return EspRequest::RESPONSE_ERROR;
    }

    /**
     * Конвертируем строку из hex цветов и возвращаем массив с GRB цветками
     * @param string $hexString
     * @return array
     */
    public static function convertHexString(string $hexString): array
    {
        $hexArray = explode(',', $hexString);
        $result = [];
        foreach ($hexArray as $hex) {
            $result = ArrayHelper::merge($result, self::convertColor($hex));
        }
        return array_values($result);
    }

    /**
     * Конвертирует hex в GRB для ws2812 nodemcu
     * @param string $hex
     * @return array
     */
    public static function convertColor(string $hex): array
    {
        $hex = trim($hex);
        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
        //echo "$hex -> $r $g $b";
        return [$r, $g, $b];
    }
}

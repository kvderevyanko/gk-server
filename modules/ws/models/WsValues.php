<?php

namespace app\modules\ws\models;

use app\models\Device;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\httpclient\Client;

/**
 * This is the model class for table "ws_values".
 *
 * @property int $id
 * @property int $deviceId
 * @property string|null $name
 * @property int|null $defaultBuffer
 * @property int|null $buffer
 * @property string|null $mode
 * @property int|null $delay
 * @property int|null $bright
 * @property string|null $singleColor
 * @property string|null $gradientColor
 * @property string|null $modeOptions
 * @property bool|null $active
 * @property bool|null $home
 */
class WsValues extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_NO_ACTIVE = 0;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ws_values';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deviceId', 'defaultBuffer'], 'required'],
            [['deviceId'], 'unique'],
            [['deviceId', 'buffer', 'defaultBuffer', 'delay', 'bright'], 'integer'],
            [['delay'], 'integer', 'min' => 4],
            [['bright'], 'integer', 'min' => 0, 'max' => 255],
            [['gradientColor'], 'string'],
            [['active', 'home'], 'boolean'],
            [['name', 'mode', 'singleColor', 'modeOptions'], 'string', 'max' => 255],
        ];
    }

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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'deviceId' => 'Основное устройство',
            'name' => 'Имя',
            'buffer' => 'Количество диодов',
            'defaultBuffer' => 'Количество диодов по умолчанию',
            'mode' => 'Режим',
            'delay' => 'Задержка',
            'bright' => 'Яркость',
            'singleColor' => 'Одиночный цвет',
            'gradientColor' => 'Градиент цветов',
            'modeOptions' => 'Опции режима',
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


    public static function sendRequest($deviceId) {

        $ws = self::findOne(['deviceId' => $deviceId, 'active' => self::STATUS_ACTIVE]);
        if($ws) {
            self::convertHexString($ws->gradientColor);
            $client = new Client([
                'transport' => 'yii\httpclient\CurlTransport'
            ]);
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl($ws->device->host.'ws.lc')
                ->setData([
                    'buffer' => $ws->buffer,
                    'mode' => $ws->mode,
                    'delay' => $ws->delay, //clock 1~1000, pwm frequency
                    'bright' => $ws->bright, //duty 0~1023, pwm duty cycle, max 1023 (10bit)
                    'single_color' => Json::encode(self::convertColor($ws->singleColor)), //grb
                    'gradient_color' => Json::encode(self::convertHexString($ws->gradientColor)),
                    'mode_options' => $ws->modeOptions,
                ])
                ->setOptions([
                    'timeout' => 2, // set timeout to 5 seconds for the case server is not responding
                ])
                ->send();
            if ($response->isOk) {
                return $response->content;
            }
        }

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

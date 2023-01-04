<?php

namespace app\commands;

use app\components\widgets\SettingValueWidget;
use app\models\Dht;
use app\models\Gpio;
use app\models\Settings;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Установка параметров по крону
 * Class DeviceController
 * @package app\commands
 */
class DeviceController extends Controller
{

    /**
     * Получение температуры
     */
    public function actionTemperature()
    {
        $dhts = Dht::findAll(['active' => Dht::STATUS_ACTIVE]);
        foreach ($dhts as $dht){
            Dht::sendRequest($dht->deviceId);
        }
    }

    /**
     * Включение мотора на 20 секунд по крону
     *--  * * * * * php  /var/www/html/yii device/motor
     *--  * * * * sleep 20; php  /var/www/html/yii device/motor
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionMotor()
    {
        $gpio = Gpio::findAll(['active' => Gpio::STATUS_ACTIVE, 'motor' => true]);
        $delay = SettingValueWidget::widget(['key' => Settings::MOTOR_INTERVAL]);
        if(!(int)$delay)
            $delay = 3;
        echo $delay;
        foreach ($gpio as $gpi){
            if(date('i', time())%$delay || $gpi->value) {
                $gpi->value = 0;
            } else {
                $gpi->value = 1;
            }

            $gpi->save();
            try {
                Gpio::sendRequest($gpi->deviceId);
            } catch (\Exception $e) {

            }
        }
    }
}

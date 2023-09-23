<?php

namespace app\commands;

use app\models\Settings;
use app\modules\gpio\models\Gpio;
use app\widgets\SettingValueWidget;
use yii\console\Controller;

/**
 * Установка параметров по крону
 * Class DeviceController
 * @package app\commands
 */
class MotorController extends Controller
{



    /**
     * Включение мотора на 20 секунд по крону
     *--  * * * * * php  /var/www/html/yii device/motor
     *--  * * * * sleep 20; php  /var/www/html/yii device/motor
     * @throws \yii\web\NotFoundHttpException|\Throwable
     */
    public function actionMotor()
    {
        $gpio = Gpio::find()
            ->where(['active' => Gpio::STATUS_ACTIVE, 'motor' => true])
            ->all();
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

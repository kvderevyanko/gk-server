<?php


namespace app\components\widgets;


use app\models\Gpio;
use app\models\PwmValues;
use yii\base\Widget;

class GpioShowWidget extends Widget
{

    public $deviceId;
    public $mainPage;

    public function run()
    {
        $gpioValues = Gpio::find()->where(['active' => PwmValues::STATUS_ACTIVE]);
        if($this->deviceId)
            $gpioValues->andWhere(['deviceId' => $this->deviceId]);
        if($this->mainPage)
            $gpioValues->andWhere(['home' => true]);

        $gpioValues = $gpioValues->all();

        if(count($gpioValues) < 1)
            return '';

        return $this->render('gpio-show', ['gpioValues' => $gpioValues]);
    }
}
<?php


namespace app\modules\gpio\widgets;

use app\modules\gpio\models\Gpio;
use yii\base\Widget;

class GpioShowWidget extends Widget
{

    public  $deviceId;
    public  $mainPage;

    /**
     * @return string
     */
    public function run(): string
    {
        $gpioValues = Gpio::find()
            ->with('device')
            ->where(['active' => Gpio::STATUS_ACTIVE]);
        if($this->deviceId)
            $gpioValues->andWhere(['deviceId' => $this->deviceId]);
        if($this->mainPage)
            $gpioValues->andWhere(['home' => true]);

        $gpioValues = $gpioValues
            ->orderBy(['deviceId' => SORT_ASC, 'pin' => SORT_ASC])
            ->all();

        if(count($gpioValues) < 1)
            return '';

        return $this->render('gpio-show', ['gpioValues' => $gpioValues]);
    }
}

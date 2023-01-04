<?php


namespace app\components\widgets;


use app\models\PwmValues;
use yii\base\Widget;

class PwmShowWidget extends Widget
{

    public $deviceId;
    public $mainPage;

    public function run()
    {
        $pwmValues = PwmValues::find()->where(['active' => PwmValues::STATUS_ACTIVE]);
        if($this->deviceId)
            $pwmValues->andWhere(['deviceId' => $this->deviceId]);
        if($this->mainPage)
            $pwmValues->andWhere(['home' => true]);

        $pwmValues = $pwmValues->all();

        if(count($pwmValues) < 1)
            return '';

        return $this->render('pwm-show', ['pwmValues' => $pwmValues]);
    }
}
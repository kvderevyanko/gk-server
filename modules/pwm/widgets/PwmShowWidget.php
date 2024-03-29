<?php


namespace app\modules\pwm\widgets;


use app\modules\pwm\models\Pwm;
use yii\base\Widget;

class PwmShowWidget extends Widget
{

    public  $deviceId;
    public  $mainPage;

    /**
     * @return string
     */
    public function run(): string
    {
        $pwmValues = Pwm::find()->where(['active' => Pwm::STATUS_ACTIVE]);
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
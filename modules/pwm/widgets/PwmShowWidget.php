<?php


namespace app\modules\pwm\widgets;


use app\modules\pwm\models\Pwm;
use app\modules\pwm\models\PwmSettings;
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
        $pwmValues = Pwm::find()->with('device')->where(['active' => Pwm::STATUS_ACTIVE]);
        if($this->deviceId)
            $pwmValues->andWhere(['deviceId' => $this->deviceId]);
        if($this->mainPage)
            $pwmValues->andWhere(['home' => true]);

        $pwmValues = $pwmValues->orderBy(['deviceId' => SORT_ASC, 'pin' => SORT_ASC])->all();

        if(count($pwmValues) < 1)
            return '';

        $deviceIds = [];
        foreach ($pwmValues as $pwmValue) {
            $deviceIds[] = (int) $pwmValue->deviceId;
        }
        $settingsByDevice = [];
        foreach (PwmSettings::find()->where(['deviceId' => array_unique($deviceIds)])->all() as $settings) {
            $settingsByDevice[(int) $settings->deviceId] = $settings;
        }

        return $this->render('pwm-show', compact('pwmValues', 'settingsByDevice'));
    }
}

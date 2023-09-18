<?php


namespace app\components\widgets;


use app\models\Device;
use yii\base\Widget;

class DeviceBtnWidget extends Widget
{


    public function run()
    {
        $devices = Device::findAll(['active' => Device::STATUS_ACTIVE, 'home' => true]);
        return $this->render('device-btn', ['devices' => $devices]);
    }
}
<?php


namespace app\components\widgets;


use app\models\Device;
use app\models\PwmValues;
use app\models\Settings;
use app\models\WsValues;
use yii\base\Widget;

class SettingValueWidget extends Widget
{

    public $key;

    public function run()
    {
        $setting = Settings::findOne(['key' => $this->key]);
        if($setting)
            return $setting->value;
        return '';
    }
}
<?php


namespace app\widgets;


use app\models\Settings;
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
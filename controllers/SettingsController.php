<?php

namespace app\controllers;


use app\models\Dht;
use app\models\Gpio;
use app\models\PwmValues;
use app\models\Settings;
use app\models\WsValues;
use yii\helpers\ArrayHelper;
use yii\web\Controller;


/**
 * Настройки
 * Class CommandsController
 * @package app\controllers
 */
class SettingsController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex(){
        $settings = Settings::find()->all();
        $settings = ArrayHelper::index($settings, 'key');

        if(\Yii::$app->request->post('Settings')) {
            $post = \Yii::$app->request->post('Settings');
            if($post && is_array($post)) {
                foreach ($post as $key => $value) {
                    if(array_key_exists($key, $settings)) {
                        $setting = $settings[$key];
                        $setting->value = $value;
                        $setting->save();
                    }
                }
                return $this->refresh();
            }
        }

        return $this->render('index', ['settings' => $settings]);
    }


}

<?php

namespace app\controllers;


use app\models\Dht;
use app\models\Gpio;
use app\models\PwmValues;
use app\models\WsValues;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;


/**
 * Выполнение команд на ноду
 * Class CommandsController
 * @package app\controllers
 */
class CommandsController extends Controller
{
    public function actionPwm($deviceId){
        $request = \Yii::$app->request->get();
        $pwmList = PwmValues::findAll(['deviceId' => $deviceId]);
        foreach ($pwmList as $pwm) {
            if(array_key_exists($pwm->id, $request)){
                $pwm->value = $request[$pwm->id];
                $pwm->save();
            }
        }
        return PwmValues::sendRequest($deviceId);
    }

    public function actionGpio($deviceId){
        $request = \Yii::$app->request->get();
        $gpioList = Gpio::findAll(['deviceId' => $deviceId]);
        foreach ($gpioList as $gpio) {
            if(array_key_exists($gpio->id, $request)){
                $gpio->value = $request[$gpio->id];
                $gpio->save();
            }
        }
        return Gpio::sendRequest($deviceId);
    }

    public function actionWs($deviceId){
        $ws = WsValues::findOne(['deviceId' => $deviceId]);
        if($ws) {
            $ws->attributes = \Yii::$app->request->get();
            if($ws->delay < 5)
                $ws->delay = 5;
            if($ws->bright < 1)
                $ws->bright = 0;
            if(!$ws->save()){
                return Json::encode($ws->errors);
            }
            return WsValues::sendRequest($deviceId);
        }
        return 'error';
    }

    public function actionDht($deviceId){
        return Dht::sendRequest($deviceId);
    }
}

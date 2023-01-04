<?php

namespace app\controllers;


use app\models\Dht;
use app\models\Gpio;
use app\models\PwmValues;
use app\models\WsValues;
use yii\helpers\ArrayHelper;
use yii\web\Controller;


class WsController extends Controller
{
    public function actionSaveAnimation($deviceId){
        print_r(\Yii::$app->request->post());
    }

}

<?php

namespace app\controllers;


use app\models\DbWsValues;
use app\modules\gpio\models\Gpio;
use app\modules\pwm\models\PwmValues;
use modules\dht\models\Dht;
use yii\helpers\Json;
use yii\web\Controller;


/**
 * Выполнение команд на ноду
 * Class CommandsController
 * @package app\controllers
 */
class CommandsController extends Controller
{




    public function actionDht($deviceId){
        return Dht::sendRequest($deviceId);
    }
}

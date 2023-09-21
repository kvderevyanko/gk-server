<?php

namespace app\controllers;


use modules\dht\models\Dht;
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

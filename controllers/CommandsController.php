<?php

namespace app\controllers;

use yii\web\Controller;


/**
 * @TO-DO К удалению
 */
class CommandsController extends Controller
{




    public function actionDht($deviceId){
        return Dht::sendRequest($deviceId);
    }
}

<?php

namespace app\controllers;


use app\models\Dht;
use app\models\Gpio;
use app\models\PwmValues;
use app\models\WsValues;
use yii\helpers\ArrayHelper;
use yii\web\Controller;


/**
 * Работа с джойстиком
 * Class CommandsController
 * @package app\controllers
 */
class GamepadController extends Controller
{
    public function actionIndex(){
        return $this->render('index');
    }
}

<?php

namespace app\modules\gamepad\controllers;


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

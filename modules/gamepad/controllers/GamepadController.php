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
    public function actionIndex(): string
    {
        return $this->render('index');
    }
}

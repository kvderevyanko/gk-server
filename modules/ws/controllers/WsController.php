<?php

namespace app\modules\ws\controllers;


use yii\web\Controller;


class WsController extends Controller
{
    public function actionSaveAnimation($deviceId){
        print_r(\Yii::$app->request->post());
    }

}

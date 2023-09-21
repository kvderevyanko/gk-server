<?php

namespace app\modules\ws\controllers;


use app\modules\ws\models\DbWsValues;
use app\modules\ws\models\WsValues;
use yii\helpers\Json;
use yii\web\Controller;


class WsController extends Controller
{

    public function actionRequest($deviceId){
        $ws = DbWsValues::findOne(['deviceId' => $deviceId]);
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

    public function actionSaveAnimation($deviceId){
        print_r(\Yii::$app->request->post());
    }

}

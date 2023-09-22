<?php

namespace app\modules\ws\controllers;


use app\modules\ws\models\WsValues;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\httpclient\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


class WsController extends Controller
{

    /**
     * @param $deviceId
     * @return string
     * @throws InvalidConfigException
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionRequest($deviceId): string
    {
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

    public function actionSaveAnimation($deviceId){
        print_r(\Yii::$app->request->post());
    }

}

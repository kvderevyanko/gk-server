<?php

namespace app\modules\dht\controllers;

use app\modules\dht\models\Dht;
use app\modules\gpio\models\Gpio;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\httpclient\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;


class RequestController extends Controller
{

    /**
     * Получение значения температуры
     * @param int $deviceId
     * @param int $pin
     * @return string
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionGetTemperature(int $deviceId, int $pin): string
    {
        return Dht::sendRequest($deviceId, $pin);
    }


}

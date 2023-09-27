<?php

namespace app\modules\dht\controllers;

use app\components\CustomHelper;
use app\modules\dht\models\Dht;
use app\modules\dht\models\TemperatureInfo;
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

    /**
     * Получение значения для графиков
     * @param int $deviceId
     * @param int $pin
     * @return array
     */
    public function actionGetGraphInfo(int $deviceId, int $pin): array
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $result = [];

        $info = TemperatureInfo::find()->select([
            'temperature',
            'humidity',
            'datetime'
            ])
            ->where([
            'deviceId' => $deviceId,
            'pin' => $pin,
            ])
            ->orderBy(['id' => SORT_DESC])
            ->limit(100)
            ->all();

        foreach ($info as $single) {
            $result[] = [
                'dateTime' => CustomHelper::formatDateTime($single->datetime),
                'humidity' => $single->humidity,
                'temperature' => $single->temperature,
            ];
        }

        return $result;

    }
}

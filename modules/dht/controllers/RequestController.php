<?php

namespace app\modules\dht\controllers;

use app\components\CustomHelper;
use app\models\CommandDelivery;
use app\modules\dht\models\Dht;
use app\modules\dht\models\TemperatureInfo;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\Controller;
use yii\helpers\Json;


class RequestController extends Controller
{
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'get-temperature' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Запрашивает и сохраняет показания одного активного DHT-датчика.
     */
    public function actionGetTemperature(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $deviceId = filter_var($request->post('deviceId'), FILTER_VALIDATE_INT);
        $pin = filter_var($request->post('pin'), FILTER_VALIDATE_INT);

        if ($deviceId === false || $deviceId === null || $deviceId < 1 || $pin === false || $pin === null || $pin < 1) {
            Yii::$app->response->statusCode = 400;

            return [
                'status' => 'error',
                'message' => 'Укажите корректные deviceId и pin.',
            ];
        }

        try {
            $result = Json::decode(Dht::sendRequest($deviceId, $pin));
        } catch (\Throwable $exception) {
            Yii::$app->response->statusCode = 502;
            CommandDelivery::record($deviceId, 'dht', CommandDelivery::STATUS_ERROR, 'Не удалось получить показания от устройства.', $pin);

            return [
                'status' => 'error',
                'message' => 'Не удалось получить показания от устройства.',
            ];
        }

        if (is_array($result) && ($result['status'] ?? null) === 'ok') {
            CommandDelivery::record(
                $deviceId,
                'dht',
                CommandDelivery::STATUS_CONFIRMED,
                $result['message'] ?? 'Показания получены и сохранены.',
                $pin,
                [
                    'temperature' => $result['temperature'] ?? null,
                    'humidity' => $result['humidity'] ?? null,
                ]
            );

            return $result;
        }

        Yii::$app->response->statusCode = 502;
        $message = is_array($result) && !empty($result['message'])
            ? $result['message']
            : 'Устройство не подтвердило получение показаний.';
        CommandDelivery::record($deviceId, 'dht', CommandDelivery::STATUS_ERROR, $message, $pin);

        return [
            'status' => 'error',
            'message' => $message,
        ];
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

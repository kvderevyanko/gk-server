<?php

namespace app\modules\gpio\controllers;

use app\modules\gpio\models\Gpio;
use Yii;
use yii\httpclient\Exception;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Класс для получения запроса из фронта с изменением значения пина
 */
class RequestController extends Controller
{
    /**
     * GPIO changes physical state, so the endpoint must not be triggered by a
     * link preview or a cached GET request.
     *
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => ['set' => ['POST']],
            ],
        ];
    }

    /**
     * Получение запроса со значением пинов для  устройства
     * @param int $deviceId
     * @param int $pin
     * @param mixed $value
     * @return array
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionSet(int $deviceId, int $pin, $value): array
    {
        if (!in_array($value, [true, false, 1, 0, '1', '0', 'true', 'false'], true)) {
            throw new BadRequestHttpException('Значение GPIO должно быть true или false.');
        }

        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (!Gpio::setStatus($deviceId, $pin, $value)) {
            throw new NotFoundHttpException('Активный GPIO не найден.');
        }

        try {
            $espResponse = Gpio::sendRequest($deviceId);
        } catch (\Throwable $exception) {
            Yii::error($exception, __METHOD__);
            Yii::$app->response->statusCode = 502;

            return [
                'status' => 'error',
                'message' => 'Не удалось связаться с устройством. Состояние сохранено, но не подтверждено.',
            ];
        }

        $decodedResponse = json_decode($espResponse, true);
        if (!is_array($decodedResponse) || ($decodedResponse['status'] ?? null) !== 'ok') {
            Yii::$app->response->statusCode = 502;

            return [
                'status' => 'error',
                'message' => 'Устройство не подтвердило команду. Состояние сохранено, но не подтверждено.',
            ];
        }

        return [
            'status' => 'ok',
            'message' => 'Команда подтверждена устройством.',
            'value' => $value,
        ];
    }
}

<?php

namespace app\modules\ws\controllers;


use app\models\CommandDelivery;
use app\modules\ws\models\WsValues;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;


class WsController extends Controller
{
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'request' => ['POST'],
                    'save-animation' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Сохраняет желаемую конфигурацию WS2812 и отправляет её на ESP.
     */
    public function actionRequest(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $deviceId = filter_var($request->post('deviceId'), FILTER_VALIDATE_INT);
        if ($deviceId === false || $deviceId === null || $deviceId < 1) {
            return $this->errorResponse(400, 'Укажите корректный deviceId.');
        }

        $ws = WsValues::findOne([
            'deviceId' => $deviceId,
            'active' => WsValues::STATUS_ACTIVE,
        ]);
        if ($ws === null) {
            return $this->errorResponse(404, 'Активная настройка WS2812 для устройства не найдена.');
        }

        $values = $request->post();
        $allowed = ['buffer', 'mode', 'delay', 'bright', 'singleColor', 'modeOptions'];
        $ws->setAttributes(array_intersect_key($values, array_flip($allowed)), false);

        if (!$this->validateCommand($ws)) {
            return $this->errorResponse(422, 'Проверьте параметры WS2812.', $ws->getFirstErrors());
        }

        if (!$ws->save(false)) {
            return $this->errorResponse(422, 'Не удалось сохранить параметры WS2812.', $ws->getFirstErrors());
        }

        try {
            $result = Json::decode(WsValues::sendRequest($deviceId));
        } catch (\Throwable $exception) {
            $message = 'Не удалось связаться с устройством. Параметры сохранены, но не подтверждены.';
            CommandDelivery::record($deviceId, 'ws', CommandDelivery::STATUS_ERROR, $message, null, $this->deliveryPayload($ws));

            return $this->errorResponse(502, $message);
        }

        if (is_array($result) && ($result['status'] ?? null) === 'ok') {
            CommandDelivery::record(
                $deviceId,
                'ws',
                CommandDelivery::STATUS_CONFIRMED,
                $result['message'] ?? 'Параметры WS2812 подтверждены устройством.',
                null,
                $this->deliveryPayload($ws)
            );

            return $result;
        }

        $message = is_array($result) && !empty($result['message'])
            ? $result['message']
            : 'Устройство не подтвердило параметры WS2812.';
        CommandDelivery::record($deviceId, 'ws', CommandDelivery::STATUS_ERROR, $message, null, $this->deliveryPayload($ws));

        return $this->errorResponse(502, $message);
    }

    public function actionSaveAnimation(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $this->errorResponse(501, 'Сохранение пользовательских анимаций пока не реализовано.');
    }

    private function validateCommand(WsValues $ws): bool
    {
        if (!$ws->validate()) {
            return false;
        }

        $maxBuffer = min(330, max(1, (int) $ws->defaultBuffer * 3));
        if ($ws->buffer < 1 || $ws->buffer > $maxBuffer) {
            $ws->addError('buffer', 'Количество диодов должно быть от 1 до ' . $maxBuffer . '.');
        }
        if (!array_key_exists($ws->mode, WsValues::$modeList)) {
            $ws->addError('mode', 'Выберите поддерживаемый режим WS2812.');
        }
        if ($ws->delay < 20 || $ws->delay > 10000) {
            $ws->addError('delay', 'Задержка должна быть от 20 до 10000 мс.');
        }
        if ($ws->bright < 1 || $ws->bright > 255) {
            $ws->addError('bright', 'Яркость должна быть от 1 до 255.');
        }
        if (filter_var($ws->modeOptions, FILTER_VALIDATE_INT) === false || $ws->modeOptions < 1 || $ws->modeOptions > 255) {
            $ws->addError('modeOptions', 'Опция режима должна быть целым числом от 1 до 255.');
        }
        if (!is_string($ws->singleColor) || !preg_match('/^#[0-9a-fA-F]{6}$/', $ws->singleColor)) {
            $ws->addError('singleColor', 'Цвет должен быть в формате #RRGGBB.');
        }

        return !$ws->hasErrors();
    }

    private function errorResponse(int $statusCode, string $message, array $errors = []): array
    {
        Yii::$app->response->statusCode = $statusCode;

        $response = [
            'status' => 'error',
            'message' => $message,
        ];
        if ($errors) {
            $response['errors'] = $errors;
        }

        return $response;
    }

    private function deliveryPayload(WsValues $ws): array
    {
        return [
            'buffer' => $ws->buffer,
            'mode' => $ws->mode,
            'delay' => $ws->delay,
            'bright' => $ws->bright,
            'singleColor' => $ws->singleColor,
            'modeOptions' => $ws->modeOptions,
        ];
    }

}

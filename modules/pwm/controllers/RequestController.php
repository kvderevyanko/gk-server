<?php
namespace app\modules\pwm\controllers;

use app\models\CommandDelivery;
use app\modules\pwm\models\Pwm;
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
    public function behaviors(): array { return ['verbs' => ['class' => VerbFilter::class, 'actions' => ['set' => ['POST']]]]; }
    /**
     * Получение запроса со значением пинов для  устройства
     * @param int $deviceId
     * @param int $pin
     * @param int $value
     * @return string
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionSet(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $deviceId = filter_var(Yii::$app->request->post('deviceId'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $pin = filter_var(Yii::$app->request->post('pin'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
        $value = filter_var(Yii::$app->request->post('value'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 1023]]);
        if ($deviceId === false || $pin === false || $value === false) throw new BadRequestHttpException('Параметры PWM некорректны.');
        if (!Pwm::setStatus($deviceId, $pin, $value)) throw new NotFoundHttpException('Активный PWM не найден.');
        try { $result = Pwm::sendRequest($deviceId); } catch (\Throwable $e) { Yii::error($e, __METHOD__); Yii::$app->response->statusCode = 502; CommandDelivery::record($deviceId, 'pwm', CommandDelivery::STATUS_ERROR, 'Не удалось связаться с устройством. Значение не подтверждено.', $pin, ['value' => $value]); return ['status' => 'error', 'message' => 'Не удалось связаться с устройством. Значение не подтверждено.']; }
        $decoded = json_decode($result, true);
        if (!is_array($decoded) || ($decoded['status'] ?? null) !== 'ok') { Yii::$app->response->statusCode = 502; CommandDelivery::record($deviceId, 'pwm', CommandDelivery::STATUS_ERROR, 'Устройство не подтвердило значение PWM.', $pin, ['value' => $value]); return ['status' => 'error', 'message' => 'Устройство не подтвердило значение PWM.']; }
        $message = 'Значение PWM подтверждено устройством.';
        CommandDelivery::record($deviceId, 'pwm', CommandDelivery::STATUS_CONFIRMED, $message, $pin, ['value' => $value]);
        return ['status' => 'ok', 'message' => $message, 'value' => $value];
    }
}

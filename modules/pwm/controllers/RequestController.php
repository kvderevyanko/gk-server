<?php
namespace app\modules\pwm\controllers;

use app\modules\pwm\models\Pwm;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Класс для получения запроса из фронта с изменением значения пина
 */
class RequestController extends Controller
{
    /**
     * Получение запроса со значением пинов для  устройства
     * @param int $deviceId
     * @param int $pin
     * @param int $value
     * @return string
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionSet(int $deviceId, int $pin, int $value): string
    {
        Pwm::setStatus($deviceId, $pin, $value);
        return Pwm::sendRequest($deviceId);
    }
}

<?php

namespace app\modules\pwm\models;

use app\components\EspRequest\EspRequest;
use app\components\EspRequest\EspRequestSenderFactory;
use app\models\Device;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\httpclient\Exception;
use yii\web\NotFoundHttpException;

class PwmValues extends DbPwmValues
{


    /**
     * @param $deviceId
     * @return string
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     * @throws Exception
     */
    public static function sendRequest($deviceId): string
    {
        $pwm = self::findAll(['deviceId' => $deviceId, 'active' => self::STATUS_ACTIVE]);
        $params = ArrayHelper::map($pwm, 'pinId', 'value');
        $pwmSettings = PwmSettings::findOne(['deviceId' => $deviceId]);
        if($pwmSettings === null)
            throw new NotFoundHttpException("Настройки PWM не найдены");

        $device = Device::getActiveDevice($deviceId);
        $params['clock'] = $pwmSettings->clock;
        $params['duty'] = $pwmSettings->duty;

        $requestSenderFactory = new EspRequestSenderFactory();
        $espRequest = $requestSenderFactory->createEspRequest($device->host,'gpio-pwm.lc', $params);
        return $espRequest->send();
    }
}

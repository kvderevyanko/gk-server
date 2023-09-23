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

class Pwm extends DbPwmValues
{


    /**
     * @param int $deviceId
     * @return string
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public static function sendRequest(int $deviceId): string
    {

        $pwmList = self::find()
            ->leftJoin(Device::tableName(), Device::tableName().".id = ".self::tableName().".deviceId")
            ->andWhere([
                Device::tableName().".active" => Device::STATUS_ACTIVE,
                self::tableName().'.deviceId' => $deviceId,
                self::tableName().'.active' => self::STATUS_ACTIVE
            ])
            ->all();
        $params = ArrayHelper::map($pwmList, 'pin', 'value');

        $device = Device::getActiveDevice($deviceId);

        $pwmSettings = PwmSettings::findOne(['deviceId' => $deviceId]);
        if($pwmSettings === null)
            throw new NotFoundHttpException("Настройки PWM не найдены");

        $params['clock'] = $pwmSettings->clock;
        $params['duty'] = $pwmSettings->duty;

        $requestSenderFactory = new EspRequestSenderFactory();
        $espRequest = $requestSenderFactory->createEspRequest($device->host,'gpio-pwm.lc', $params);
        return $espRequest->send();
    }

    public static function setStatus(int $deviceId, int $pin, int $value): bool
    {
        $pwm = self::find()
            ->leftJoin(Device::tableName(), Device::tableName().".id = ".self::tableName().".deviceId")
            ->andWhere([
                self::tableName().'.deviceId' => $deviceId,
                self::tableName().'.active' => self::STATUS_ACTIVE,
                self::tableName().'.pin' => $pin,
                Device::tableName().".active" => Device::STATUS_ACTIVE,

            ])
            ->one();
        if($pwm) {
            $pwm->value = $value;
            return $pwm->save();
        }
        return false;
    }
}
